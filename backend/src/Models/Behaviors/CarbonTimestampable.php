<?php

namespace Backend\Models\Behaviors;


use Carbon\Carbon;
use Phalcon\Mvc\Model\Behavior;

class CarbonTimestampable extends Behavior
{

    public function notify($type, \Phalcon\Mvc\ModelInterface $model)
    {

        parent::notify($type, $model);

        // Create and update

        $writeFields = null;

        if ($type === 'beforeValidationOnCreate') {

            $writeFields = $this->getOptions('create');

        } elseif ($type === 'beforeValidationOnUpdate') {

            $writeFields = $this->getOptions('update');

        }

        if ($writeFields) {
            if (is_array($writeFields)) {
                foreach ($writeFields as $field) {
                    $model->writeAttribute($field, Carbon::now());
                }
            } else {
                $model->writeAttribute($writeFields, Carbon::now());
            }
        }

        // Change raw date

        if ($type === 'afterFetch' || $type === 'afterSave') {

            $fields = array_merge((array)$this->getOptions('create'), (array)$this->getOptions('update'));

            foreach ($fields as $field) {
                $model->writeAttribute($field, Carbon::parse($model->{$field}));
            }

        }

        // Prepare date to db

        if($type === 'beforeSave'){

            $fields = array_merge((array)$this->getOptions('create'), (array)$this->getOptions('update'));

            foreach ($fields as $field) {

                $value = $model->{$field};

                if($value && $value instanceof Carbon) {
                    $model->writeAttribute($field, $value->toDateTimeString('microsecond'));
                }
            }
        }
    }

}