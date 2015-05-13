<?php

namespace Unit\SprykerFeature\Zed\Sales\Business\Model;

use SprykerFeature\Zed\Sales\Business\Model\OrderExporter;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\Decorator\CsvDecorator;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\OrderExporterStrategy\CsvStrategy;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\ElementExporter\OrderElement;
use Propel\Runtime\Collection\Collection;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Sales
 * @group Business
 */
class OrderExporterTest extends \PHPUnit_Framework_TestCase
{

    public function testSetOrderCollectionShouldReturnExporter()
    {
        $order = new SpySalesOrder();
        $orderCollection = new Collection();
        $orderCollection->append($order);
        $strategy = new CsvStrategy();
        $strategy->setFilePath(__DIR__ . '/fixture/export.csv');
        $exporter = new OrderExporter($strategy);
        $this->assertInstanceOf('SprykerFeature\Zed\Sales\Business\Model\OrderExporter', $exporter->setOrderCollection($orderCollection));
    }

    public function testExportShouldThrowExceptionWhenNoOrderOrOrderCollectionWasProvided()
    {
        $this->setExpectedException('InvalidArgumentException');
        $strategy = new CsvStrategy();
        $strategy->setFilePath(__DIR__ . '/fixture/export.csv');
        $exporter = new OrderExporter($strategy);
        $this->assertTrue($exporter->export());
    }

    public function testSetElementExporterShouldReturnInstanceOfExporter()
    {
        $strategy = new CsvStrategy();
        $strategy->setFilePath(__DIR__ . '/fixture/export.csv');
        $exporter = new OrderExporter($strategy);
        $elementExporter = [
            new OrderElement()
        ];
        $this->assertInstanceOf('SprykerFeature\Zed\Sales\Business\Model\OrderExporter', $exporter->setElementExporter($elementExporter));
    }

    public function testExportShouldReturnTrue()
    {
//        $this->markTestSkipped('EntityLoader not longer supported');
        $order = new SpySalesOrder();
        $orderCollection = new Collection();
        $orderCollection->append($order);
        $csvDecorator = new CsvDecorator([CsvDecorator::SEPARATOR => ';']);
        $strategy = new CsvStrategy([CsvStrategy::LINE_FEED => "\r\n"]);
        $strategy->setFilePath(__DIR__ . '/fixture/export.csv');
        $exporter = new OrderExporter($strategy);
        $exporter->setOrderCollection($orderCollection);
        $elementExporter = [
            (new OrderElement())->setDecorator($csvDecorator)
        ];
        $exporter->setElementExporter($elementExporter);
        $this->assertTrue($exporter->export());
    }
}
