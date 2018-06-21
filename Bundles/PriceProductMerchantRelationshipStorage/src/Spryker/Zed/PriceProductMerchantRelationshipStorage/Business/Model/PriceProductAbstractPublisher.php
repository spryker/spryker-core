<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepository;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface;

class PriceProductAbstractPublisher implements PriceProductAbstractPublisherInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface
     */
    protected $PriceProductMerchantRelationshipStorageEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface
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
        $abstractProducts = $this->PriceProductMerchantRelationshipStorageRepository
            ->findPriceProductStoreListByIdsForAbstract($priceProductStoreIds);

        $PriceProductMerchantRelationshipStorageTransferCollection = $this->priceGrouper->getGroupedPrices(
            $abstractProducts,
            PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_ABSTRACT_ID_PRODUCT,
            PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_ABSTRACT_SKU
        );

        if (count($PriceProductMerchantRelationshipStorageTransferCollection) === 0) {
            return;
        }

        // if we have few prices new prices will not be published
        $PriceProductMerchantRelationshipStorageEntityMap = $this->PriceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductAbstractMerchantRelationshipStorageEntities($abstractProducts);

        $this->PriceProductMerchantRelationshipStorageEntityManager->writePriceProductAbstract(
            $PriceProductMerchantRelationshipStorageTransferCollection,
            $PriceProductMerchantRelationshipStorageEntityMap
        );
    }

    /**
     * @param array $merchantRelationshipAbstractProducts
     *
     * @return void
     */
    public function unpublish(array $merchantRelationshipAbstractProducts): void
    {
        foreach ($merchantRelationshipAbstractProducts as $idMerchantRelationship => $idAbstractProducts) {
            foreach ($idAbstractProducts as $idProductAbstract) {
                $this->PriceProductMerchantRelationshipStorageEntityManager
                    ->deletePriceProductAbstractByMerchantRelationshipAndIdProductAbstract($idMerchantRelationship, $idProductAbstract);
            }
        }
    }
}
