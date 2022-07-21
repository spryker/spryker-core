<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\Storage;

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
     * @param string $exporterIdTouch
     * @param array<string, mixed> $data
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
     * @param array<string, mixed> $data
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
