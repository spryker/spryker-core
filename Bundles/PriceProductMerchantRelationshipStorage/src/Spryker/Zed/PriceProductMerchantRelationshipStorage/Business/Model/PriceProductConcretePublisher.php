<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepository;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface;

class PriceProductConcretePublisher implements PriceProductConcretePublisherInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface
     */
    protected $PriceProductMerchantRelationshipStorageEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipRepositoryInterface
     */
    protected $PriceProductMerchantRelationshipStorageRepository;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface
     */
    protected $priceGrouper;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface $PriceProductMerchantRelationshipStorageEntityManager
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface $PriceProductMerchantRelationshipStorageRepository
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface $priceGrouper
     */
    public function __construct(
        PriceProductMerchantRelationshipStorageEntityManagerInterface $PriceProductMerchantRelationshipStorageEntityManager,
        PriceProductMerchantRelationshipStorageRepositoryInterface $PriceProductMerchantRelationshipStorageRepository,
        PriceGrouperInterface $priceGrouper
    ) {
        $this->PriceProductMerchantRelationshipStorageEntityManager = $PriceProductMerchantRelationshipStorageEntityManager;
        $this->PriceProductMerchantRelationshipStorageRepository = $PriceProductMerchantRelationshipStorageRepository;
        $this->priceGrouper = $priceGrouper;
    }

    /**
     * @param array $priceProductStoreIds
     *
     * @return void
     */
    public function publish(array $priceProductStoreIds): void
    {
        $concreteProducts = $this->PriceProductMerchantRelationshipStorageRepository
            ->findPriceProductStoreListByIdsForConcrete($priceProductStoreIds);

        $groupedPrices = $this->priceGrouper->getGroupedPrices(
            $concreteProducts,
            PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_CONCRETE_ID_PRODUCT,
            PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_CONCRETE_SKU
        );

        if (count($groupedPrices) === 0) {
            return;
        }

        $PriceProductMerchantRelationshipStorageEntityMap = $this->PriceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductConcreteMerchantRelationshipStorageEntities($concreteProducts);

        $this->PriceProductMerchantRelationshipStorageEntityManager->writePriceProductConcrete(
            $groupedPrices,
            $PriceProductMerchantRelationshipStorageEntityMap
        );
    }

    /**
     * @param array $merchantRelationshipConcreteProducts
     *
     * @return void
     */
    public function unpublish(array $merchantRelationshipConcreteProducts): void
    {
        foreach ($merchantRelationshipConcreteProducts as $idMerchantRelationship => $idProducts) {
            foreach ($idProducts as $idProduct) {
                $this->PriceProductMerchantRelationshipStorageEntityManager
                    ->deletePriceProductConcreteByMerchantRelationshipAndIdProduct($idMerchantRelationship, $idProduct);
            }
        }
    }
}
