<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\Decorator;

/**
 * Interface FieldFormatterInterface
 * @package SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter
 */
interface DecoratorInterface
{

    /**
     * @param array $fields
     * @return mixed
     */
    public function decorate(array $fields);
}
