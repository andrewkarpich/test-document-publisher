<?php

namespace Backend\Models\Behaviors;


use Closure;
use Phalcon\Mvc\Model\Behavior;

class BeforeAfterSave extends Behavior
{

    public function notify($type, \Phalcon\Mvc\ModelInterface $model)
    {

        parent::notify($type, $model);

        $fields = null;

        if ($type === 'afterFetch' || $type === 'afterSave') {

            // Normalization

            $fields = $this->getOptions('after');

        }

        if ($type === 'beforeSave') {

            // Prepare data to db

            $fields = $this->getOptions('before');

        }

        if ($fields && is_array($fields)) {

            foreach ($fields as $fieldName => $callable) {

                if ($callable instanceof Closure) {

                    $result = $callable($model->{$fieldName});

                    if ($result) {
                        $model->writeAttribute($fieldName, $result);
                    }

                }

            }

        }

    }

}