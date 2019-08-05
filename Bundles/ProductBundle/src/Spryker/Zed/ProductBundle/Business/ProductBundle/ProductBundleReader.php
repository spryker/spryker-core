<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use ArrayObject;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

class ProductBundleReader implements ProductBundleReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface
     */
    protected $productBundleRepository;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface
     */
    protected $availabilityQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface $productBundleRepository
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductBundleRepositoryInterface $productBundleRepository,
        ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer,
        ProductBundleToStoreFacadeInterface $storeFacade
    ) {
        $this->productBundleRepository = $productBundleRepository;
        $this->availabilityQueryContainer = $availabilityQueryContainer;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function findBundledProductsByIdProductConcrete($idProductConcrete)
    {
        return new ArrayObject(
            $this->productBundleRepository->findBundledProductsByIdProductConcrete($idProductConcrete)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function assignBundledProductsToProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        $productConcreteTransfer->requireIdProductConcrete()->requireSku();

        $bundledProducts = $this->findBundledProductsByIdProductConcrete(
            $productConcreteTransfer->getIdProductConcrete()
        );

        if (count($bundledProducts) === 0) {
            return $productConcreteTransfer;
        }

        $productBundleTransfer = new ProductBundleTransfer();
        $productBundleTransfer->setBundledProducts($bundledProducts);

        $productBundleAvailabilityEntity = $this->findOrCreateProductBundleAvailabilityEntity($productConcreteTransfer);
        if ($productBundleAvailabilityEntity !== null) {
            $productBundleTransfer->setAvailability($productBundleAvailabilityEntity->getQuantity());
            $productBundleTransfer->setIsNeverOutOfStock($productBundleAvailabilityEntity->getIsNeverOutOfStock());
        }

        $productConcreteTransfer->setProductBundle($productBundleTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailability
     */
    protected function findOrCreateProductBundleAvailabilityEntity(ProductConcreteTransfer $productConcreteTransfer)
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        return $this->availabilityQueryContainer
            ->querySpyAvailabilityBySku($productConcreteTransfer->getSku(), $storeTransfer->getIdStore())
            ->findOneOrCreate();
    }
}
