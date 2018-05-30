<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductPackagingUnit\Business\Installer\ProductPackagingUnitTypeInstaller;
use Spryker\Zed\ProductPackagingUnit\Business\Installer\ProductPackagingUnitTypeInstallerInterface;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Reader\ProductPackagingUnitTypeReader;
use Spryker\Zed\ProductPackagingUnit\Business\Model\Reader\ProductPackagingUnitTypeReaderInterface;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\ProductPackagingUnitConfig getConfig()
 */
class ProductPackagingUnitBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Installer\ProductPackagingUnitTypeInstallerInterface
     */
    public function createProductPackagingUnitTypeInstaller(): ProductPackagingUnitTypeInstallerInterface
    {
        return new ProductPackagingUnitTypeInstaller(
            $this->getEntityManager(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPackagingUnit\Business\Model\Reader\ProductPackagingUnitTypeReaderInterface
     */
    public function createProductPackagingUnitTypeReader(): ProductPackagingUnitTypeReaderInterface
    {
        return new ProductPackagingUnitTypeReader(
            $this->getRepository()
        );
    }
}
