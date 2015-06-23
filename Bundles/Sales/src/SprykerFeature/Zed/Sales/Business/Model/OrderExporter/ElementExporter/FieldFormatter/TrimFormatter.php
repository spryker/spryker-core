<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter;

/**
 * Class TrimFormatter
 * @package SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter
 */
class TrimFormatter extends AbstractFieldFormatter
{

    /**
     * @param $value
     * @return mixed
     */
    public function format($value)
    {
        return trim($value);
    }
}
