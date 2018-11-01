<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Business\Model;

use Orm\Zed\PriceProductMerchantRelationshipStorage\Persistence\SpyPriceProductAbstractMerchantRelationshipStorage;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Persistence\PriceProductMerchantRelationshipStorageEntityManagerInterface;
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
     * @param int[] $companyBusinessUnitIds
     *
     * @return void
     */
    public function publishByBusinessUnits(array $companyBusinessUnitIds): void
    {
        $priceProductMerchantRelationshipStorageTransfers = $this->priceProductMerchantRelationshipStorageRepository
            ->getProductAbstractPriceDataByCompanyBusinessUnitIds($companyBusinessUnitIds);

        $this->write($priceProductMerchantRelationshipStorageTransfers, $companyBusinessUnitIds);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param array $businessUnitProducts
     *
     * @return void
     */
    public function publishByBusinessUnitProducts(array $businessUnitProducts): void
    {
        $this->publishByBusinessUnits(array_keys($businessUnitProducts));
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer[] $priceProductMerchantRelationshipStorageTransfers
     * @param int[] $companyBusinessUnitIds
     *
     * @return void
     */
    protected function write(array $priceProductMerchantRelationshipStorageTransfers, array $companyBusinessUnitIds): void
    {
        $mappedPriceProductMerchantRelationshipStorageEntities = $this->createExistingPriceProductMerchantRelationshipStorageEntitiesMap($companyBusinessUnitIds);
        $priceProductMerchantRelationshipStorageTransfers = $this->priceGrouper->groupPrices(
            $priceProductMerchantRelationshipStorageTransfers
        );

        foreach ($priceProductMerchantRelationshipStorageTransfers as $merchantRelationshipStorageTransfer) {
            if (isset($mappedPriceProductMerchantRelationshipStorageEntities[$merchantRelationshipStorageTransfer->getPriceKey()])) {
                $this->priceProductMerchantRelationshipStorageEntityManager->updatePriceProductAbstract(
                    $mappedPriceProductMerchantRelationshipStorageEntities[$merchantRelationshipStorageTransfer->getPriceKey()],
                    $merchantRelationshipStorageTransfer
                );

                unset($mappedPriceProductMerchantRelationshipStorageEntities[$merchantRelationshipStorageTransfer->getPriceKey()]);
                continue;
            }

            $this->priceProductMerchantRelationshipStorageEntityManager->createPriceProductAbstract(
                $merchantRelationshipStorageTransfer
            );
        }

        // Delete the rest of the entites
        $this->priceProductMerchantRelationshipStorageEntityManager->deletePriceProductAbstracts(
            array_map(function (SpyPriceProductAbstractMerchantRelationshipStorage $priceProductMerchantRelationshipStorageEntity) {
                $priceProductMerchantRelationshipStorageEntity->getIdPriceProductAbstractMerchantRelationshipStorage();
            }, $mappedPriceProductMerchantRelationshipStorageEntities)
        );

        unset($mappedPriceProductMerchantRelationshipStorageEntities);
    }

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return array
     */
    protected function createExistingPriceProductMerchantRelationshipStorageEntitiesMap(array $companyBusinessUnitIds)
    {
        $existingPriceProductMerchantRelationshipStorageEntities = $this->priceProductMerchantRelationshipStorageRepository
            ->findExistingPriceProductAbstractMerchantRelationshipStorageEntities($companyBusinessUnitIds);

        $mappedPriceProductMerchantRelationshipStorageEntities = [];
        foreach ($existingPriceProductMerchantRelationshipStorageEntities as $priceProductMerchantRelationshipStorageEntity) {
            $mappedPriceProductMerchantRelationshipStorageEntities[$priceProductMerchantRelationshipStorageEntity->getPriceKey()] =
                $priceProductMerchantRelationshipStorageEntity;
        }

        unset($existingPriceProductMerchantRelationshipStorageEntities);

        return $mappedPriceProductMerchantRelationshipStorageEntities;
    }
}
