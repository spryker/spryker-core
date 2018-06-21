<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Business\Model;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer;
use Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipEntityManagerInterface;
use Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipRepositoryInterface;

class MerchantRelationshipPriceWriter implements MerchantRelationshipPriceWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipEntityManagerInterface
     */
    protected $priceProductBusinessUnitEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipRepositoryInterface
     */
    protected $priceProductBusinessUnitRepository;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipEntityManagerInterface $priceProductBusinessUnitEntityManager
     * @param \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipRepositoryInterface $priceProductBusinessUnitRepository
     * @param \Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(
        PriceProductMerchantRelationshipEntityManagerInterface $priceProductBusinessUnitEntityManager,
        PriceProductMerchantRelationshipRepositoryInterface $priceProductBusinessUnitRepository,
        PriceProductMerchantRelationshipToPriceProductFacadeInterface $priceProductFacade
    ) {
        $this->priceProductBusinessUnitEntityManager = $priceProductBusinessUnitEntityManager;
        $this->priceProductBusinessUnitRepository = $priceProductBusinessUnitRepository;
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function save(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        $priceProductTransfer
            ->requireMoneyValue()
            ->requirePriceDimension();

        $priceDimensionTransfer = $priceProductTransfer->getPriceDimension();
        $priceDimensionTransfer->requireIdBusinessUnit();

        $idPriceProductStoreBeforeUpdate = $priceProductTransfer->getMoneyValue()->getIdEntity();

        $priceProductTransfer = $this->priceProductFacade->persistPriceProductStore($priceProductTransfer);

        if (!$this->isPriceStoreRelationChanged($priceProductTransfer, $idPriceProductStoreBeforeUpdate)) {
            return $priceProductTransfer;
        }

        if ($idPriceProductStoreBeforeUpdate) {
            $this->priceProductBusinessUnitEntityManager->deleteByIdPriceProductStoreAndIdMerchantRelationship(
                $idPriceProductStoreBeforeUpdate,
                $priceDimensionTransfer->getIdBusinessUnit()
            );
        }

        $priceProductBusinessUnitEntityTransfer = $this->getPriceProductBusinessUnitEntityTransfer($priceProductTransfer);
        $this->priceProductBusinessUnitEntityManager->saveEntity($priceProductBusinessUnitEntityTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    public function deleteByIdBusinessUnit(int $idCompanyBusinessUnit): void
    {
        $this->priceProductBusinessUnitEntityManager->deleteByIdMerchantRelationship($idCompanyBusinessUnit);
    }

    /**
     * @return void
     */
    public function deleteAll(): void
    {
        $this->priceProductBusinessUnitEntityManager->deleteAll();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int|null $idPriceProductStoreBeforeUpdate
     *
     * @return bool
     */
    protected function isPriceStoreRelationChanged(PriceProductTransfer $priceProductTransfer, $idPriceProductStoreBeforeUpdate): bool
    {
        return !$idPriceProductStoreBeforeUpdate || $priceProductTransfer->getMoneyValue()->getIdEntity() !== $idPriceProductStoreBeforeUpdate;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer
     */
    protected function getPriceProductBusinessUnitEntityTransfer(PriceProductTransfer $priceProductTransfer): SpyPriceProductMerchantRelationshipEntityTransfer
    {
        $priceProductBusinessUnitEntityTransfer = (new SpyPriceProductMerchantRelationshipEntityTransfer())
            ->setFkCompanyBusinessUnit($priceProductTransfer->getPriceDimension()->getIdBusinessUnit())
            ->setFkPriceProductStore($priceProductTransfer->getMoneyValue()->getIdEntity());

        if ($priceProductTransfer->getIdProductAbstract()) {
            $priceProductBusinessUnitEntityTransfer->setFkProductAbstract($priceProductTransfer->getIdProductAbstract());
        } else {
            $priceProductBusinessUnitEntityTransfer->setFkProduct($priceProductTransfer->getIdProduct());
        }

        return $priceProductBusinessUnitEntityTransfer;
    }
}
