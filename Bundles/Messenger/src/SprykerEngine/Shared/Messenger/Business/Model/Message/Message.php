<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Messenger\Business\Model\Message;

class Message implements MessageInterface
{

    const MESSAGE_ALERT = 'alert';
    const MESSAGE_CRITICAL = 'critical';
    const MESSAGE_DEBUG = 'debug';
    const MESSAGE_EMERGENCY = 'emergency';
    const MESSAGE_ERROR = 'error';
    const MESSAGE_INFO = 'info';
    const MESSAGE_NOTICE = 'notice';
    const MESSAGE_SUCCESS = 'success';
    const MESSAGE_WARNING = 'warning';

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * @param string $type
     * @param string $message
     * @param array $options
     */
    public function __construct($type, $message, $options = [])
    {
        $this->type = $type;
        $this->message = $message;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

}
