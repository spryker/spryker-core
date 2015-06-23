<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter;

/**
 * Class Trim
 * @package SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter
 */
abstract class AbstractFieldFormatter implements FieldFormatterInterface
{

    /**
     * @var string
     */
    private $fieldName;

    /**
     * @param $fieldName
     */
    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param $value
     * @return mixed
     */
    abstract public function format($value);
}
