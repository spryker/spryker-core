<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter\Writer;

interface WriterInterface
{

    /**
     * @param array $dataSet
     * @param string $type
     *
     * @return bool
     */
    public function write(array $dataSet, $type = '');

    /**
     * @param array $dataSet
     *
     * @return bool
     */
    public function delete(array $dataSet);

    /**
     * @return string
     */
    public function getName();

}
