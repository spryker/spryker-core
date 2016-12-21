<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle;

use ArrayObject;
use Generated\Shared\Transfer\ProductBundleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductForBundleTransfer;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleReader implements ProductBundleReaderInterface
{

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface
     */
    protected $availabilityQueryContainer;

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer
     */
    public function __construct(
        ProductBundleQueryContainerInterface $productBundleQueryContainer,
        ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer
    ) {
        $this->productBundleQueryContainer = $productBundleQueryContainer;
        $this->availabilityQueryContainer = $availabilityQueryContainer;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \ArrayObject
     */
    public function findBundledProductsByIdProductConcrete($idProductConcrete)
    {
        $bundledProducts = $this->productBundleQueryContainer
            ->queryBundleProduct($idProductConcrete)
            ->joinWithSpyProductRelatedByFkBundledProduct()
            ->find();

        $bundledProductsTransferCollection = new ArrayObject();
        foreach ($bundledProducts as $bundledProductEntity) {
            $productForBundleTransfer = new ProductForBundleTransfer();
            $productForBundleTransfer->setIdProductConcrete($bundledProductEntity->getFkBundledProduct());

            $sku = $bundledProductEntity->getSpyProductRelatedByFkBundledProduct()->getSku();
            $productForBundleTransfer->setSku($sku);

            $productForBundleTransfer->fromArray($bundledProductEntity->toArray(), true);
            $bundledProductsTransferCollection->append($productForBundleTransfer);
        }

        return $bundledProductsTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function assignBundledProductsToProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
         $productConcreteTransfer->requireIdProductConcrete();

         $bundledProducts = $this->findBundledProductsByIdProductConcrete(
             $productConcreteTransfer->getIdProductConcrete()
         );

        if (count($bundledProducts) === 0) {
            return $productConcreteTransfer;
        }

         $productBundleTransfer = new ProductBundleTransfer();

         $productBundleTransfer->setBundledProducts($bundledProducts);

         $productBundleAvailabilityEntity = $this->availabilityQueryContainer
             ->querySpyAvailabilityBySku($productConcreteTransfer->getSku())
             ->findOneOrCreate();

        if ($productBundleAvailabilityEntity !== null) {
            $productBundleTransfer->setAvailability($productBundleAvailabilityEntity->getQuantity());
        }

         $productConcreteTransfer->setProductBundle($productBundleTransfer);

         return $productConcreteTransfer;
    }

}
