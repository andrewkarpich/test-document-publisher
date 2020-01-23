<?php

namespace Backend\Validators;


use Phalcon\Messages\Message;
use Phalcon\Validation;
use Phalcon\Validation\AbstractValidator;

class UuidValidator extends AbstractValidator
{

    public function validate(Validation $validation, $field): bool
    {

        $label = $this->getOption('label');

        if (empty($label)) {
            $label = $validation->getLabel($field);
        }


        $value = $validation->getValue($field);

        if ($this->getOption('allowEmpty') && empty($value)) {
            return true;
        }


        $uuidRegEx = "/^\{?[A-Z0-9]{8}-[A-Z0-9]{4}-[1-5][A-Z0-9]{3}-[A-Z0-9]{4}-[A-Z0-9]{12}\}?$/i";

        if (!preg_match($uuidRegEx, $value)) {
            $message = $this->getOption("message");

            if (empty($message)) {
                $message = 'Field :field must be a valid UUID';
            }


            $replacePairs = [
                ":field" => $label,
            ];

            $validation->appendMessage(
                new Message(
                    strtr($message, $replacePairs),
                    $field,
                    "Uuid"
                )
            );

            return false;
        }


        $allowedVersions = $this->getOption("allowedVersions");

        if (empty($allowedVersions)) {
            $allowedVersions = [1, 2, 3, 4, 5];
        }


        if (!in_array(substr($value, 14, 1), $allowedVersions)) {
            $message = $this->getOption("messageVersion");

            if (empty($message)) {
                $message = 'Field :field must be one of the following UUID versions: :versions';
            }


            $replacePairs = [
                ':field'    => $label,
                ':versions' => implode(", ", $allowedVersions),
            ];

            $validation->appendMessage(
                new Message(
                    strtr($message, $replacePairs),
                    $field,
                    'Uuid'
                )
            );

            return false;
        }

        return true;

    }
}