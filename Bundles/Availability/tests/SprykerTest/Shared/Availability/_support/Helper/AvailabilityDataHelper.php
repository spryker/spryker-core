<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Availability\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AvailabilityDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    protected const QUANTITY = 10;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract
     */
    public function haveAvailabilityAbstract(ProductConcreteTransfer $productConcreteTransfer): SpyAvailabilityAbstract
    {
        $availabilityFacade = $this->getAvailabilityFacade();
        $idAvailabilityAbstract = $availabilityFacade->saveProductAvailability($productConcreteTransfer->getSku(), static::QUANTITY);
        $idStore = $this->getStoreFacade()->getCurrentStore()->getIdStore();

        return $this
            ->getAvailabilityQueryContainer()
            ->queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract, $idStore)
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
