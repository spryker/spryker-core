<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Reader;

interface ReaderInterface
{

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function read($key);

    /**
     * @return string
     */
    public function getName();

}
