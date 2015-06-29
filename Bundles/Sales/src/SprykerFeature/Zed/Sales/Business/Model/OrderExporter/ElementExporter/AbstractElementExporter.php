<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\OrderExporterStrategy;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter\FieldFormatterInterface;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\FieldFormatter\FieldFormatterException;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\Decorator\DecoratorInterface;

abstract class AbstractElementExporter implements ElementExporterInterface
{

    /**
     * @var \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder
     */
    protected $order;

    /**
     * @var OrderExporterStrategy
     */
    protected $strategy;

    /**
     * @var array
     */
    protected $exportFields = [];

    /**
     * @var FieldFormatterInterface[]
     */
    protected $fieldFormatter = [];

    /**
     * @var DecoratorInterface
     */
    protected $decorator;

    /**
     * @param DecoratorInterface $decorator
     * @return $this
     */
    public function setDecorator(DecoratorInterface $decorator)
    {
        $this->decorator = $decorator;
        return $this;
    }

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @param OrderExporterStrategy $strategy
     * @return $this
     */
    public function setStrategy(OrderExporterStrategy $strategy)
    {
        $this->strategy = $strategy;
        return $this;
    }

    /**
     * @param array $exportFields
     * @return $this
     */
    public function setExportFields(array $exportFields)
    {
        $this->exportFields = $exportFields;
        return $this;
    }

    /**
     * @param array $fieldFormatter
     * @return $this
     */
    public function setFieldFormatter(array $fieldFormatter)
    {
        foreach ($fieldFormatter as $formatter) {
            $this->addFieldFormatter($formatter);
        }
        return $this;
    }

    /**
     * @param FieldFormatterInterface $fieldFormatter
     * @return $this
     */
    public function addFieldFormatter(FieldFormatterInterface $fieldFormatter)
    {
        $this->fieldFormatter = $fieldFormatter;
        return $this;
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return \SprykerFeature_Zed_Library_Propel_BaseObject
     */
    abstract protected function getOrderElement(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order);

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return mixed|void
     */
    public function export(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order)
    {
        $this->order = $order;
        $exportElement = $this->getOrderElement($order);
        $exportFields = $this->getExportFields($exportElement);
        $exportFields = $this->formatExportFields($exportFields);
        $result = $this->decorate($exportFields);
        $this->strategy->handleElementExporterResult($result);
        $this->notifyStrategy();
    }

    /**
     * @param array $exportableFields
     * @return array|mixed
     */
    protected function decorate(array $exportableFields)
    {
        if (!$this->decorator) {
            return $exportableFields;
        } else {
            return $this->decorator->decorate($exportableFields);
        }
    }

    /**
     * This method is ment to be used with a ActiveRecordInterface e.g. a SalesOrderItem,
     * but to have the opertunity to override this method and use anything else please don´t use a type hint
     *
     * @param $element
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function getExportFields($element)
    {
        if (!($element instanceof ActiveRecordInterface)) {
            throw new \InvalidArgumentException('Passed element to "getExportFields" must be a instance of "ActiveRecordInterface" or must be overwriten in your exporter!');
        }
        $exportFields = [];
        $orderElementArray = $element->toArray();
        foreach ($this->exportFields as $key => $exportValue) {
            $value = $this->exportFields[$key];
            if (is_callable($value)) {
                $value = $value($this->order, $this);
            } elseif (is_bool($value) && $value) {
                $value = $orderElementArray[$key];
            }
            $exportFields[$key] = $value;
        }

        return $exportFields;
    }

    /**
     * @param array $exportFields
     * @return array
     * @throws FieldFormatter\FieldFormatterException
     */
    protected function formatExportFields(array $exportFields)
    {
        foreach ($this->fieldFormatter as $formatter) {
            if ($formatter->getFieldName() === FieldFormatterInterface::FORMAT_ALL_FIELDS) {
                foreach ($exportFields as $key => $value) {
                    $exportFields[$key] = $formatter->format($value);
                }
                continue;
            }
            if (!array_key_exists($formatter->getFieldName(), $exportFields)) {
                throw new FieldFormatterException('Export fields doesn\'t contain a field by name "' . $$formatter->getFieldName() . '"!');
            }
            $exportFields[$formatter->getFieldName()] = $formatter->format($exportFields[$formatter->getFieldName()]);
        }
        return $exportFields;
    }

    /**
     * Hook to tell strategy what ever you want
     */
    protected function notifyStrategy()
    {
    }

}
