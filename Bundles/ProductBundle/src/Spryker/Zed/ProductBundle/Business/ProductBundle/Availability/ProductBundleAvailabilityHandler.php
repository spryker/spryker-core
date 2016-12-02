<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Availability;

use Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface;

class ProductBundleAvailabilityHandler
{

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface
     */
    protected $availabilityQueryContainer;

    /**
     * @var \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer
     * @param \Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityInterface $availabilityFacade
     */
    public function __construct(
        ProductBundleToAvailabilityQueryContainerInterface $availabilityQueryContainer,
        ProductBundleToAvailabilityInterface $availabilityFacade
    ) {
        $this->availabilityQueryContainer = $availabilityQueryContainer;
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    public function updateBundleAvailability($sku)
    {
        $bundleProducts = SpyProductBundleQuery::create()
            ->useSpyProductRelatedByFkBundledProductQuery()
                ->filterBySku($sku)
            ->endUse()
            ->find();

        foreach ($bundleProducts as $bundleProductEntity) {

            $bundleItemSku = $bundleProductEntity->getSpyProductRelatedByFkProduct()
                ->getSku();

            $bundleProductAvailability = $this->availabilityQueryContainer
                ->querySpyAvailabilityBySku($bundleItemSku)
                ->findOne();

            $bundledItems = SpyProductBundleQuery::create()
                ->filterByFkProduct($bundleProductEntity->getFkProduct())
                ->find();

            $maxQty = 0;
            $maxQtyAvailability = 0;
            foreach ($bundledItems as $bundledItemEntity) {
                $bundledItemQuantity = $bundledItemEntity->getQuantity();
                $bundledItemSku = $bundledItemEntity->getSpyProductRelatedByFkBundledProduct()->getSku();

                $bundledProductAvailability = $this->availabilityQueryContainer
                    ->querySpyAvailabilityBySku($bundledItemSku)
                    ->findOne();

                if ($bundledItemQuantity > $maxQty) {
                    $maxQty = $bundledItemQuantity;
                    $maxQtyAvailability = $bundledProductAvailability->getQuantity();
                }
            }

            $bundleAvailabilityQuantity = floor($maxQtyAvailability / $maxQty);

            $bundleProductAvailability->setQuantity($bundleAvailabilityQuantity)->save();

            $this->updateAbstractAvailabilityQuantity($bundleProductAvailability->getFkAvailabilityAbstract());
            $this->availabilityFacade->touchAvailabilityAbstract($bundleProductAvailability->getFkAvailabilityAbstract());
        }
    }

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return void
     */
    protected function updateAbstractAvailabilityQuantity($idAvailabilityAbstract)
    {
        $availabilityAbstractEntity = $this->availabilityQueryContainer
            ->queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract)
            ->findOne();

        $sumQuantity = (int)$this->availabilityQueryContainer
            ->querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract)
            ->findOne();

        $availabilityAbstractEntity->setQuantity($sumQuantity);
        $availabilityAbstractEntity->save();
    }

}
