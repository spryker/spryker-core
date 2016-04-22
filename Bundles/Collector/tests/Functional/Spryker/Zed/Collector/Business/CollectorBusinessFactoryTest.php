<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Collector\Business;

use Spryker\Zed\Collector\Business\CollectorBusinessFactory;

/**
 * @group Spryker
 * @group Zed
 * @group Collector
 * @group Business
 * @group CollectorBusinessFactory
 */
class CollectorBusinessFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Collector\Business\CollectorBusinessFactory
     */
    private function getFactory()
    {
        return new CollectorBusinessFactory();
    }

    /**
     * @return void
     */
    public function testCreateYvesFileExporterShouldReturnFullyConfiguredInstance()
    {
        $yvesFileExporter = $this->getFactory()->createYvesFileExporter();

        $this->assertInstanceOf('\Spryker\Zed\Collector\Business\Exporter\CollectorExporter', $yvesFileExporter);
    }

    /**
     * @return void
     */
    public function testCreateFileWriterBuilderShouldReturnFullyConfiguredInstance()
    {
        $fileWriterBuilder = $this->getFactory()->createFileWriterBuilder();

        $this->assertInstanceOf('\Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriterBuilder', $fileWriterBuilder);
    }

}
