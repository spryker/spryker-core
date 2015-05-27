<?php

namespace SprykerEngine\Shared\Messenger\Business\Model\Message;

interface MessageInterface
{

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return array
     */
    public function getOptions();

}
