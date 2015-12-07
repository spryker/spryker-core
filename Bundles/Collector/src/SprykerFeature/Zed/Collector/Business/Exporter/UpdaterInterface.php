<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

interface UpdaterInterface
{

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getData($key);

    /**
     * @param $key
     * @param array $dataToAppend
     *
     * @return void
     */
    public function updateData($key, array $dataToAppend);

}
