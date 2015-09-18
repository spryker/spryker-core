<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue;

class TouchUpdaterSet
{

    const TOUCH_EXPORTER_ID = 'exporter_touch_id';

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param $key
     * @param $idTouch
     * @param null $data
     */
    public function add($key, $idTouch, $data = null)
    {
        $this->data[$key] = [
            self::TOUCH_EXPORTER_ID => $idTouch,
            'data' => $data,
        ];
    }

}
