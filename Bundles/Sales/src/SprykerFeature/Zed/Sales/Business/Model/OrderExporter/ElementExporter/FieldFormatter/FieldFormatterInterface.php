<?php

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter;

/**
 * Interface FieldFormatterInterface
 * @package SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter
 */
interface FieldFormatterInterface
{

    const FORMAT_ALL_FIELDS = '*';

    /**
     * @return string
     */
    public function getFieldName();

    /**
     * @param $value
     * @return mixed
     */
    public function format($value);
}
