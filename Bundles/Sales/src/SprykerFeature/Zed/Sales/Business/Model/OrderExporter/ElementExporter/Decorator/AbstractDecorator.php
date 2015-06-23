<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\Decorator;

/**
 * Interface FieldFormatterInterface
 * @package SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter
 */
abstract class AbstractDecorator implements DecoratorInterface
{

    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }
}
