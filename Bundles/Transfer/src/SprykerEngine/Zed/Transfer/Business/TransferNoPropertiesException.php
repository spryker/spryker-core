<?php

namespace SprykerEngine\Zed\Transfer\Business;

class TransferNoPropertiesException extends \Exception
{
    /**
     * @var int
     */
    protected $code = 401;

    /**
     * @var string
     */
    protected $message = 'Called Transfer class has no properties and getters declared';

    /**
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ': ['.$this->code.']: '. $this->message . "\n";
    }
}
