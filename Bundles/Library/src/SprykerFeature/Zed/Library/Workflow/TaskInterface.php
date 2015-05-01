<?php
namespace SprykerFeature\Zed\Library\Workflow;

interface TaskInterface
{
    /**
     * @return bool
     */
    public function isSuccess();

    /**
     * @return array
     */
    public function getErrors();
}
