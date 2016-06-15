<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Collector\Business;

use Spryker\Zed\Collector\Business\CollectorBusinessFactory;
use Spryker\Zed\Collector\Business\Exporter\CollectorExporter;
use Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriterBuilderInterface;

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

        $this->assertInstanceOf(CollectorExporter::class, $yvesFileExporter);
    }

}
