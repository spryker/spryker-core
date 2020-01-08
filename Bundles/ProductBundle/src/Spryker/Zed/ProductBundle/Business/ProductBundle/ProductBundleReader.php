<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use ArrayObject;
use Generated\Shared\Transfer\ProductBundleCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

class ProductBundleReader implements ProductBundleReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface
     */
    protected $productBundleRepository;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface $productBundleRepository
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        ProductBundleRepositoryInterface $productBundleRepository,
        ProductBundleToAvailabilityFacadeInterface $availabilityFacade,
        ProductBundleToStoreFacadeInterface $storeFacade
    ) {
        $this->productBundleRepository = $productBundleRepository;
        $this->availabilityFacade = $availabilityFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[]
     */
    public function findBundledProductsByIdProductConcrete($idProductConcrete)
    {
        $productBundleCriteriaFilterTransfer = (new ProductBundleCriteriaFilterTransfer())
            ->setIdProductConcrete($idProductConcrete);

        $productBundleCollectionTransfer = $this->productBundleRepository
            ->getProductBundleCollectionByCriteriaFilter($productBundleCriteriaFilterTransfer);

        $productForBundleTransfers = new ArrayObject();
        foreach ($productBundleCollectionTransfer->getProductBundles() as $productBundleTransfer) {
            foreach ($productBundleTransfer->getBundledProducts() as $productForBundleTransfer) {
                $productForBundleTransfers->append($productForBundleTransfer);
            }
        }

        return $productForBundleTransfers;
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

        $productBundleAvailabilityTransfer = $this->findProductConcreteAvailabilityBySkuForStore($productConcreteTransfer);
        if ($productBundleAvailabilityTransfer !== null) {
            $productBundleTransfer->setAvailability($productBundleAvailabilityTransfer->getAvailability());
            $productBundleTransfer->setIsNeverOutOfStock($productBundleAvailabilityTransfer->getIsNeverOutOfStock());
        }

        $productConcreteTransfer->setProductBundle($productBundleTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    protected function findProductConcreteAvailabilityBySkuForStore(ProductConcreteTransfer $productConcreteTransfer): ?ProductConcreteAvailabilityTransfer
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();

        return $this->availabilityFacade
            ->findOrCreateProductConcreteAvailabilityBySkuForStore($productConcreteTransfer->getSku(), $storeTransfer);
    }
}
