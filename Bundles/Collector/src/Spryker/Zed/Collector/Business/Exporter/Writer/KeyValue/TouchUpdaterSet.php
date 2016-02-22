<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer\KeyValue;

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
            self::TOUCH_EXPORTER_ID => $idTouch,
            'data' => $data,
        ];
    }

}
