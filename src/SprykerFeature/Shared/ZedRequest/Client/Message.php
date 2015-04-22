<?php

namespace SprykerFeature\Shared\ZedRequest\Client;

class Message extends AbstractObject
{
    protected $values = [
        'data' => [],
        'message' => null
    ];

    /**
     * @return array
     */
    public function getData()
    {
        return $this->values['data'];
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->values['data'] = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->values['message'];
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->values['message'] = $message;
        return $this;
    }
}
