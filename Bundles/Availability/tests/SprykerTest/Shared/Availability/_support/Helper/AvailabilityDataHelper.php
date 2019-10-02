<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Availability\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AvailabilityDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    protected const DEFAULT_QUANTITY = 10;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Spryker\DecimalObject\Decimal|null $quantity
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract
     */
    public function haveAvailabilityAbstract(ProductConcreteTransfer $productConcreteTransfer, ?Decimal $quantity = null): SpyAvailabilityAbstract
    {
        $storeTransfer = $this->getStoreFacade()->getCurrentStore();
        $availabilityFacade = $this->getAvailabilityFacade();
        $idAvailabilityAbstract = $availabilityFacade->saveProductAvailabilityForStore(
            $productConcreteTransfer->getSku(),
            $quantity ?? new Decimal(static::DEFAULT_QUANTITY),
            $storeTransfer
        );

        return $this
            ->getAvailabilityQueryContainer()
            ->queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract, $storeTransfer->getIdStore())
            ->findOneOrCreate();
    }

    /**
     * @return \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    private function getAvailabilityFacade(): AvailabilityFacadeInterface
    {
        return $this->getLocator()->availability()->facade();
    }

    /**
     * @return \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface
     */
    private function getAvailabilityQueryContainer(): AvailabilityQueryContainerInterface
    {
        return $this->getLocator()->availability()->queryContainer();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getLocator()->store()->facade();
    }
}
