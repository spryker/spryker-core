<?php

namespace SprykerFeature\Zed\FrontendExporter\Business\Exporter\Writer;

interface WriterInterface
{
    /**
     * @param array  $dataSet
     * @param string $type
     *
     * @return bool
     */
    public function write(array $dataSet, $type = '');

    /**
     * @return string
     */
    public function getName();
}
