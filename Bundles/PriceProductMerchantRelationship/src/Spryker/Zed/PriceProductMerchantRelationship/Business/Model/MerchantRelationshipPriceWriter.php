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
    protected $priceProductMerchantRelationshipEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipRepositoryInterface
     */
    protected $priceProductMerchantRelationshipRepository;

    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipEntityManagerInterface $priceProductMerchantRelationshipEntityManager
     * @param \Spryker\Zed\PriceProductMerchantRelationship\Persistence\PriceProductMerchantRelationshipRepositoryInterface $priceProductMerchantRelationshipRepository
     * @param \Spryker\Zed\PriceProductMerchantRelationship\Dependency\Facade\PriceProductMerchantRelationshipToPriceProductFacadeInterface $priceProductFacade
     */
    public function __construct(
        PriceProductMerchantRelationshipEntityManagerInterface $priceProductMerchantRelationshipEntityManager,
        PriceProductMerchantRelationshipRepositoryInterface $priceProductMerchantRelationshipRepository,
        PriceProductMerchantRelationshipToPriceProductFacadeInterface $priceProductFacade
    ) {
        $this->priceProductMerchantRelationshipEntityManager = $priceProductMerchantRelationshipEntityManager;
        $this->priceProductMerchantRelationshipRepository = $priceProductMerchantRelationshipRepository;
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
        $priceDimensionTransfer->requireIdMerchantRelationship();

        $idPriceProductStoreBeforeUpdate = $priceProductTransfer->getMoneyValue()->getIdEntity();

        $priceProductTransfer = $this->priceProductFacade->persistPriceProductStore($priceProductTransfer);

        if (!$this->isPriceStoreRelationChanged($priceProductTransfer, $idPriceProductStoreBeforeUpdate)) {
            return $priceProductTransfer;
        }

        if ($idPriceProductStoreBeforeUpdate) {
            $this->priceProductMerchantRelationshipEntityManager->deleteByIdPriceProductStoreAndIdMerchantRelationship(
                $idPriceProductStoreBeforeUpdate,
                $priceDimensionTransfer->getIdMerchantRelationship()
            );
        }

        $priceProductMerchantRelationshipEntityTransfer = $this->getPriceProductMerchantRelationshipEntityTransfer($priceProductTransfer);
        $this->priceProductMerchantRelationshipEntityManager->saveEntity($priceProductMerchantRelationshipEntityTransfer);

        return $priceProductTransfer;
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return void
     */
    public function deleteByIdMerchantRelationship(int $idMerchantRelationship): void
    {
        $this->priceProductMerchantRelationshipEntityManager->deleteByIdMerchantRelationship($idMerchantRelationship);
    }

    /**
     * @param int $idPriceProductStore
     *
     * @return void
     */
    public function deleteByIdPriceProductStore(int $idPriceProductStore): void
    {
        $this->priceProductMerchantRelationshipEntityManager->deleteByIdPriceProductStore($idPriceProductStore);
    }

    /**
     * @return void
     */
    public function deleteAll(): void
    {
        $this->priceProductMerchantRelationshipEntityManager->deleteAll();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param int|null $idPriceProductStoreBeforeUpdate
     *
     * @return bool
     */
    protected function isPriceStoreRelationChanged(PriceProductTransfer $priceProductTransfer, ?int $idPriceProductStoreBeforeUpdate): bool
    {
        return !$idPriceProductStoreBeforeUpdate || $priceProductTransfer->getMoneyValue()->getIdEntity() !== $idPriceProductStoreBeforeUpdate;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductMerchantRelationshipEntityTransfer
     */
    protected function getPriceProductMerchantRelationshipEntityTransfer(PriceProductTransfer $priceProductTransfer): SpyPriceProductMerchantRelationshipEntityTransfer
    {
        $priceProductMerchantRelationshipEntityTransfer = (new SpyPriceProductMerchantRelationshipEntityTransfer())
            ->setFkMerchantRelationship($priceProductTransfer->getPriceDimension()->getIdMerchantRelationship())
            ->setFkPriceProductStore((string)$priceProductTransfer->getMoneyValue()->getIdEntity());

        if ($priceProductTransfer->getIdProductAbstract()) {
            $priceProductMerchantRelationshipEntityTransfer->setFkProductAbstract($priceProductTransfer->getIdProductAbstract());
        } else {
            $priceProductMerchantRelationshipEntityTransfer->setFkProduct($priceProductTransfer->getIdProduct());
        }

        return $priceProductMerchantRelationshipEntityTransfer;
    }
}
