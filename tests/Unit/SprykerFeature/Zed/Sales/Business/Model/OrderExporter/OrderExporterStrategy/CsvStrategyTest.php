<?php

namespace Unit\SprykerFeature\Zed\Sales\Business\Model\OrderExporter\OrderExporterStrategy;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter;
use SprykerFeature\Zed\Sales\Business\Model\OrderExporter\OrderExporterStrategy\CsvStrategy;

/**
 * Class OrderExportStrategyTest
 * @package Unit\SprykerFeature\Zed\Sales\Business\Model
 * @group OrderExport
 */
class CsvStrategyTest extends \PHPUnit_Framework_TestCase
{

    public function testSetFilePathShouldCreateDirIfNotExist()
    {
        $strategy = new CsvStrategy();
        $dirToCreate = __DIR__ . '/fixture';
        $this->assertFalse(is_dir($dirToCreate));
        $strategy->setFilePath($dirToCreate . '/fileName.csv');
        $this->assertTrue(is_dir($dirToCreate));
        rmdir(__DIR__ . '/fixture');
    }

    public function testFinishExportShouldThrowExceptionIfLineFeedIsNotSet()
    {
        $this->setExpectedException('Exception');
        $strategy = new CsvStrategy();
        $strategy->finishExport();
    }
}
