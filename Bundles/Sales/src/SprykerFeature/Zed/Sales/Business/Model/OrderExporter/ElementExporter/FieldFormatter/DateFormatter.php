<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter;

/**
 * Class DateFormatter
 * @package SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter
 */
class DateFormatter extends AbstractFieldFormatter
{

    /**
     * @var
     */
    protected $format;

    /**
     * @param $fieldName
     * @param $format
     */
    public function __construct($fieldName, $format)
    {
        parent::__construct($fieldName);
        $this->format = $format;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function format($value)
    {
        $dateTime = new \DateTime($value);
        return $dateTime->format($this->format);
    }
}
