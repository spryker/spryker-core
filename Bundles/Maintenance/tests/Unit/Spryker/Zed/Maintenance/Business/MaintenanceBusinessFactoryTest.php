<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Maintenance\Business\Model;

use Generated\Shared\Transfer\InstalledPackagesTransfer;
use Spryker\Zed\Maintenance\Business\InstalledPackages\InstalledPackageCollectorInterface;
use Spryker\Zed\Maintenance\Business\InstalledPackages\MarkDownWriter;
use Spryker\Zed\Maintenance\Business\MaintenanceBusinessFactory;

/**
 * @group Spryker
 * @group Zed
 * @group Maintenance
 * @group Business
 * @group MaintenanceBusinessFactory
 */
class MaintenanceBusinessFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Maintenance\Business\MaintenanceBusinessFactory
     */
    private function getFactory()
    {
        return new MaintenanceBusinessFactory();
    }

    /**
     * @return void
     */
    public function testCreatePackageCollectorShouldReturnFullConfiguredInstance()
    {
        $this->assertInstanceOf(
            InstalledPackageCollectorInterface::class,
            $this->getFactory()->createPackageCollector()
        );
    }

    /**
     * @return void
     */
    public function testCreateMarkDownWriterShouldReturnFullConfiguredInstance()
    {
        $this->assertInstanceOf(
            MarkDownWriter::class,
            $this->getFactory()->createMarkDownWriter(new InstalledPackagesTransfer())
        );
    }

}
