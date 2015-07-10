<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Workflow;

/**
 * Default abstract implementation of the task interface
 */
abstract class Task implements TaskInterface
{

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Append an error message which automatically triggers this task as not successful
     *
     * @param string $errorMessage
     */
    protected function addError($errorMessage)
    {
        $this->errors[] = $errorMessage;
    }

    /**
     * Appends multiple error messages which automatically trigger this task as not successful
     *
     * @param array $errorMessages
     */
    protected function addErrors(array $errorMessages)
    {
        foreach ($errorMessages as $message) {
            $this->addError($message);
        }
    }

    /**
     * If no error messages have been set task is successful otherwise not
     *
     * @return bool
     */
    public function isSuccess()
    {
        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

}
