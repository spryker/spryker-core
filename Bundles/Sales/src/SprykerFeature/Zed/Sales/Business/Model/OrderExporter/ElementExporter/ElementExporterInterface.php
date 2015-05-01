<?php

namespace SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter;

use SprykerFeature\Zed\Sales\Business\Model\OrderExporter;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\OrderExporterStrategy;

/**
 * Interface ElementExporter
 * @package SprykerFeature\Zed\Sales\Business\Model\OrderExporter
 */
interface ElementExporterInterface
{

    /**
     * @return string
     */
    public function getName();

    /**
     * @param OrderExporterStrategy $strategy
     * @return $this
     */
    public function setStrategy(OrderExporterStrategy $strategy);

    /**
     * @param array $exportFields
     * @return $this
     */
    public function setExportFields(array $exportFields);

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order
     * @return mixed
     */
    public function export(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder $order);
}
