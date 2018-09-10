<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Availability\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract;
use Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AvailabilityDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer
     */
    public function haveAvailabilityAbstract(ProductConcreteTransfer $productConcreteTransfer): SpyAvailabilityAbstractEntityTransfer
    {
        $availabilityFacade = $this->getAvailabilityFacade();
        $idAvailabilityAbstract = $availabilityFacade->saveProductAvailability($productConcreteTransfer->getSku(), 10);

        $storageClient = $this->getStorageClient();

        $availabilityAbstractEntityTransfer = $storageClient->getAvailabilityAbstract($productConcreteTransfer->getFkProductAbstract());
        $availabilityAbstractEntityTransfer->setIdAvailabilityAbstract($idAvailabilityAbstract);

        $spyAvailabilityAbstract = $this->getSpyAvailabilityAbstract($productConcreteTransfer);

        if ($spyAvailabilityAbstract) {
            $availabilityAbstractEntityTransfer->setQuantity($spyAvailabilityAbstract->getQuantity());
        }

        return $availabilityAbstractEntityTransfer;
    }

    /**
     * @return \Spryker\Client\AvailabilityStorage\AvailabilityStorageClientInterface
     */
    private function getStorageClient(): AvailabilityStorageClientInterface
    {
        return $this->getLocator()->availabilityStorage()->client();
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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return null|\Orm\Zed\Availability\Persistence\SpyAvailabilityAbstract
     */
    protected function getSpyAvailabilityAbstract(ProductConcreteTransfer $productConcreteTransfer): ?SpyAvailabilityAbstract
    {
        return $this->getAvailabilityQueryContainer()
            ->querySpyAvailabilityAbstractByAbstractSku($productConcreteTransfer->getAbstractSku())
            ->findOne();
    }
}
