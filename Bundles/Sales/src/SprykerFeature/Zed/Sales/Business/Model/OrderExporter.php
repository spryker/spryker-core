<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Business\Model;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\ElementExporterInterface;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\OrderExporterStrategy;

/**
 * Class OrderExporter
 * @package SprykerFeature\Zed\Sales\Business\Model
 */
class OrderExporter
{

    /**
     * @var \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder
     */
    private $orderCollection;

    /**
     * @var ElementExporterInterface[]
     */
    private $elementExporter = [];

    /**
     * @var OrderExporterStrategy
     */
    private $strategy;

    /**
     * @param OrderExporterStrategy $strategy
     */
    public function __construct(OrderExporterStrategy $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $orderCollection
     * @return $this
     */
    public function setOrderCollection(\Propel\Runtime\Collection\Collection $orderCollection)
    {
        $this->orderCollection = $orderCollection;
        $this->strategy->setOrderCollection($orderCollection);
        return $this;
    }

    /**
     * @param array $elementExporter
     * @return $this
     */
    public function setElementExporter(array $elementExporter)
    {
        foreach ($elementExporter as $exporter) {
            $this->addElementExporter($exporter);
        }
        return $this;
    }

    /**
     * @param ElementExporterInterface $elementExporter
     * @return $this
     */
    public function addElementExporter(ElementExporterInterface $elementExporter)
    {
        $elementExporter->setStrategy($this->strategy);
        $this->elementExporter[$elementExporter->getName()] = $elementExporter;
        return $this;
    }

    /**
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function export()
    {
        if (!$this->orderCollection) {
            throw new \InvalidArgumentException('You must provide a collection of orders! Use $exporter->setOrderCollection($orderCollection)');
        }
        foreach ($this->orderCollection as $order) {
            foreach ($this->elementExporter as $exporter) {
                $exporter->export($order);
            }
        }
        return $this->strategy->finishExport();
    }
}
