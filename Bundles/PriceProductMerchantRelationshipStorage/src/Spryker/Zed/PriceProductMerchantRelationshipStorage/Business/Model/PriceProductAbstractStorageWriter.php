<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepository;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface;

class PriceProductAbstractStorageWriter implements PriceProductAbstractStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface
     */
    protected $priceProductMerchantRelationshipStorageEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface
     */
    protected $priceProductMerchantRelationshipStorageRepository;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface
     */
    protected $priceGrouper;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface $priceProductMerchantRelationshipStorageEntityManager
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageRepositoryInterface $priceProductMerchantRelationshipStorageRepository
     * @param \Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model\PriceGrouperInterface $priceGrouper
     */
    public function __construct(
        PriceProductMerchantRelationshipStorageEntityManagerInterface $priceProductMerchantRelationshipStorageEntityManager,
        PriceProductMerchantRelationshipStorageRepositoryInterface $priceProductMerchantRelationshipStorageRepository,
        PriceGrouperInterface $priceGrouper
    ) {
        $this->priceProductMerchantRelationshipStorageEntityManager = $priceProductMerchantRelationshipStorageEntityManager;
        $this->priceProductMerchantRelationshipStorageRepository = $priceProductMerchantRelationshipStorageRepository;
        $this->priceGrouper = $priceGrouper;
    }

    /**
     * @param array $priceProductStoreIds
     *
     * @return void
     */
    public function publish(array $priceProductStoreIds): void
    {
        $abstractProducts = $this->priceProductMerchantRelationshipStorageRepository
            ->findPriceProductStoreListByIdsForAbstract($priceProductStoreIds);

        $priceProductMerchantRelationshipStorageTransferCollection = $this->priceGrouper->getGroupedPrices(
            $abstractProducts,
            PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_ABSTRACT_ID_PRODUCT,
            PriceProductMerchantRelationshipStorageRepository::COL_PRODUCT_ABSTRACT_SKU
        );

        if (count($priceProductMerchantRelationshipStorageTransferCollection) === 0) {
            return;
        }

        // if we have few prices new prices will not be published
        $priceProductMerchantRelationshipStorageEntityMap = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductAbstractMerchantRelationshipStorageEntities($abstractProducts);

        $this->priceProductMerchantRelationshipStorageEntityManager->writePriceProductAbstract(
            $priceProductMerchantRelationshipStorageTransferCollection,
            $priceProductMerchantRelationshipStorageEntityMap
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
                $this->priceProductMerchantRelationshipStorageEntityManager
                    ->deletePriceProductAbstractByMerchantRelationshipAndIdProductAbstract($idMerchantRelationship, $idProductAbstract);
            }
        }
    }
}
