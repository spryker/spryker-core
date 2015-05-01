<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model;

class Logger
{

    /**
     * @var Logger
     */
    protected static $instance;

    /**
     * @var array
     */
    protected $log = array();

    /**
     * @return $this
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * @param $data
     */
    public function log($data)
    {
        $this->log[] = $data;
    }

    /**
     * @return array
     */
    public function getLog()
    {
        return $this->log;
    }
}
