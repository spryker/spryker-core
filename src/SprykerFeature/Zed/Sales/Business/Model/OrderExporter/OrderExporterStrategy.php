<?php

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter;

/**
 * Class OrderExporterStrategy
 * @package SprykerFeature\Zed\Sales\Business\Model\OrderExporter
 */
abstract class OrderExporterStrategy
{

    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Propel\Runtime\Collection\Collection
     */
    private $orderCollection;

    /**
     * @var int
     */
    private $exportedOrderItems = 0;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection $orderCollection
     * @return $this
     */
    public function setOrderCollection(\Propel\Runtime\Collection\Collection $orderCollection)
    {
        $this->orderCollection = $orderCollection;
        return $this;
    }

    /**
     * @param array $result
     * @return void
     */
    abstract public function handleElementExporterResult($result);

    /**
     * @param int $count
     */
    public function updateExportedItemsCount($count = 1)
    {
        $this->exportedOrderItems += $count;
        if ($this->exportedOrderItems === $this->orderCollection->count()) {
            $this->finishExport();
        }
    }

    /**
     * @return bool
     */
    abstract public function finishExport();

}
