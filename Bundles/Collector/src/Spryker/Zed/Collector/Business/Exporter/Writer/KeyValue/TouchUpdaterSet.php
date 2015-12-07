<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\KeyValue;

class TouchUpdaterSet
{

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $exporterIdTouch;

    /**
     * @param array $data
     */
    public function __construct($exporterIdTouch, array $data = [])
    {
        $this->exporterIdTouch = $exporterIdTouch;
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
     *
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     * @param int $idTouch
     * @param array|null $data
     *
     * @return void
     */
    public function add($key, $idTouch, $data = null)
    {
        $this->data[$key] = [
            $this->exporterIdTouch => $idTouch,
            'data' => $data,
        ];
    }

}
