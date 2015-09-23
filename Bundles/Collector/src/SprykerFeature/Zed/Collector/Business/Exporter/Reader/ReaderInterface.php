<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Reader;

interface ReaderInterface
{

    /**
     * @param string $key
     * @param string $type
     *
     * @return string
     */
    public function read($key, $type = '');

    /**
     * @return string
     */
    public function getName();

}
