<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Persistence;

use Generated\Shared\Transfer\DiscountConfiguratorTransfer;
use Generated\Shared\Transfer\DiscountVoucherTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Zed\Discount\Business\Exception\PersistenceException;
use Spryker\Zed\Discount\Business\Voucher\VoucherEngineInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class DiscountPersist implements DiscountPersistInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Discount\Business\Voucher\VoucherEngineInterface
     */
    protected $voucherEngine;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @var \Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationWriterInterface
     */
    protected $discountStoreRelationWriter;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountPostCreatePluginInterface[]
     */
    protected $discountPostCreatePlugins;

    /**
     * @var \Spryker\Zed\Discount\Dependency\Plugin\DiscountPostUpdatePluginInterface[]
     */
    protected $discountPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\Discount\Business\Voucher\VoucherEngineInterface $voucherEngine
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     * @param \Spryker\Zed\Discount\Business\Persistence\DiscountStoreRelationWriterInterface $discountStoreRelationWriter
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountPostCreatePluginInterface[] $discountPostCreatePlugins
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountPostUpdatePluginInterface[] $discountPostUpdatePlugins
     */
    public function __construct(
        VoucherEngineInterface $voucherEngine,
        DiscountQueryContainerInterface $discountQueryContainer,
        DiscountStoreRelationWriterInterface $discountStoreRelationWriter,
        array $discountPostCreatePlugins,
        array $discountPostUpdatePlugins
    ) {
        $this->voucherEngine = $voucherEngine;
        $this->discountQueryContainer = $discountQueryContainer;
        $this->discountStoreRelationWriter = $discountStoreRelationWriter;
        $this->discountPostCreatePlugins = $discountPostCreatePlugins;
        $this->discountPostUpdatePlugins = $discountPostUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return int
     */
    public function save(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        $discountEntity = $this->createDiscountEntity();
        $this->hydrateDiscountEntity($discountConfiguratorTransfer, $discountEntity);

        $this->handleDatabaseTransaction(function () use ($discountEntity, $discountConfiguratorTransfer) {
            $this->executeSaveDiscountTransaction($discountEntity, $discountConfiguratorTransfer);
        });

        return $discountEntity->getIdDiscount();
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return void
     */
    protected function executeSaveDiscountTransaction(
        SpyDiscount $discountEntity,
        DiscountConfiguratorTransfer $discountConfiguratorTransfer
    ) {
        $discountConfiguratorTransfer->getDiscountGeneral()->requireStoreRelation();

        if ($discountConfiguratorTransfer->getDiscountGeneral()->getDiscountType() === DiscountConstants::TYPE_VOUCHER) {
            $this->saveVoucherPool($discountEntity);
        }

        $discountEntity->save();

        $this->saveDiscountMoneyValues($discountEntity, $discountConfiguratorTransfer);
        $this->saveDiscountStoreRelation(
            $discountConfiguratorTransfer->getDiscountGeneral()->getStoreRelation(),
            $discountEntity->getIdDiscount()
        );

        $discountConfiguratorTransfer->getDiscountGeneral()->setIdDiscount($discountEntity->getIdDiscount());

        $this->executePostCreatePlugins($discountConfiguratorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\PersistenceException
     *
     * @return bool
     */
    public function update(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        $idDiscount = $discountConfiguratorTransfer
            ->requireDiscountGeneral()
            ->getDiscountGeneral()
            ->requireIdDiscount()
            ->getIdDiscount();

        $discountEntity = $this->discountQueryContainer
            ->queryDiscount()
            ->findOneByIdDiscount($idDiscount);

        if (!$discountEntity) {
            throw new PersistenceException(
                sprintf(
                    'Discount with id "%d" not found in database.',
                    $idDiscount
                )
            );
        }

        $affectedRows = $this->handleDatabaseTransaction(function () use ($discountEntity, $discountConfiguratorTransfer) {
            return $this->executeUpdateDiscountTransaction($discountEntity, $discountConfiguratorTransfer);
        });

        return $affectedRows > 0;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return int
     */
    protected function executeUpdateDiscountTransaction(
        SpyDiscount $discountEntity,
        DiscountConfiguratorTransfer $discountConfiguratorTransfer
    ) {

        $this->hydrateDiscountEntity($discountConfiguratorTransfer, $discountEntity);
        $this->updateVoucherPool($discountConfiguratorTransfer, $discountEntity);

        $affectedRows = $discountEntity->save();

        $this->saveDiscountMoneyValues($discountEntity, $discountConfiguratorTransfer);
        $this->updateDiscountStoreRelation($discountConfiguratorTransfer->getDiscountGeneral()->getStoreRelation());

        $this->executePostUpdatePlugins($discountConfiguratorTransfer);

        return $affectedRows;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\PersistenceException
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    public function saveVoucherCodes(DiscountVoucherTransfer $discountVoucherTransfer)
    {
        $discountVoucherTransfer->requireIdDiscount();

        $discountEntity = $this->discountQueryContainer
            ->queryDiscount()
            ->findOneByIdDiscount($discountVoucherTransfer->getIdDiscount());

        if (!$discountEntity) {
            throw new PersistenceException(
                sprintf(
                    'Discount with id "%d" not found in database.',
                    $discountVoucherTransfer->getIdDiscount()
                )
            );
        }

        return $this->persistVoucherCodes($discountVoucherTransfer, $discountEntity);
    }

    /**
     * @param int $idDiscount
     * @param bool $isActive
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\PersistenceException
     *
     * @return bool
     */
    public function toggleDiscountVisibility($idDiscount, $isActive = false)
    {
        $discountEntity = $this->discountQueryContainer
            ->queryDiscount()
            ->findOneByIdDiscount($idDiscount);

        if (!$discountEntity) {
            throw new PersistenceException(
                sprintf(
                    'Discount with id "%d" not found in database.',
                    $idDiscount
                )
            );
        }

        $discountEntity->setIsActive($isActive);
        $affectedRows = $discountEntity->save();

        return $affectedRows > 0;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool
     */
    protected function saveVoucherPool(SpyDiscount $discountEntity)
    {
        /** @var \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool|null $discountVoucherPoolEntity */
        $discountVoucherPoolEntity = $discountEntity->getVoucherPool();
        if ($discountVoucherPoolEntity) {
            return $discountVoucherPoolEntity;
        }

        $discountVoucherPoolEntity = $this->createVoucherPoolEntity();
        $this->hydrateVoucherPoolEntity($discountEntity, $discountVoucherPoolEntity);
        if ($discountEntity->getIdDiscount()) {
            $discountVoucherPoolEntity->save();
        }

        $discountEntity->setVoucherPool($discountVoucherPoolEntity);

        return $discountVoucherPoolEntity;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return void
     */
    protected function detachVoucherPool(SpyDiscount $discountEntity)
    {
        $discountEntity->setFkDiscountVoucherPool(null);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return void
     */
    protected function updateVoucherPool(
        DiscountConfiguratorTransfer $discountConfiguratorTransfer,
        SpyDiscount $discountEntity
    ) {
        if ($discountConfiguratorTransfer->getDiscountGeneral()->getDiscountType() === DiscountConstants::TYPE_CART_RULE) {
            $this->detachVoucherPool($discountEntity);
        }

        if ($discountConfiguratorTransfer->getDiscountGeneral()->getDiscountType() === DiscountConstants::TYPE_VOUCHER) {
            $this->saveVoucherPool($discountEntity);
        }
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool $discountVoucherPoolEntity
     *
     * @return void
     */
    protected function hydrateVoucherPoolEntity(
        SpyDiscount $discountEntity,
        SpyDiscountVoucherPool $discountVoucherPoolEntity
    ) {
        $discountVoucherPoolEntity->setName($discountEntity->getDisplayName());
        $discountVoucherPoolEntity->setIsActive(true);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return void
     */
    protected function hydrateDiscountEntity(
        DiscountConfiguratorTransfer $discountConfiguratorTransfer,
        SpyDiscount $discountEntity
    ) {
        $discountEntity->fromArray($discountConfiguratorTransfer->getDiscountGeneral()->toArray());
        $discountEntity->setAmount($discountConfiguratorTransfer->getDiscountCalculator()->getAmount());
        $discountEntity->setCalculatorPlugin($discountConfiguratorTransfer->getDiscountCalculator()->getCalculatorPlugin());
        $discountEntity->setCollectorQueryString($discountConfiguratorTransfer->getDiscountCalculator()->getCollectorQueryString());
        $discountEntity->setDecisionRuleQueryString($discountConfiguratorTransfer->getDiscountCondition()->getDecisionRuleQueryString());
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountVoucherTransfer $discountVoucherTransfer
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return \Generated\Shared\Transfer\VoucherCreateInfoTransfer
     */
    protected function persistVoucherCodes(
        DiscountVoucherTransfer $discountVoucherTransfer,
        SpyDiscount $discountEntity
    ) {

        $discountVoucherPoolEntity = $this->saveVoucherPool($discountEntity);

        $discountEntity->setFkDiscountVoucherPool($discountVoucherPoolEntity->getIdDiscountVoucherPool());
        $discountEntity->save();

        $discountVoucherTransfer->setFkDiscountVoucherPool($discountVoucherPoolEntity->getIdDiscountVoucherPool());

        return $this->voucherEngine->createVoucherCodes($discountVoucherTransfer);
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscount
     */
    protected function createDiscountEntity()
    {
        return new SpyDiscount();
    }

    /**
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPool
     */
    protected function createVoucherPoolEntity()
    {
        return new SpyDiscountVoucherPool();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function executePostCreatePlugins(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        foreach ($this->discountPostCreatePlugins as $discountPostSavePlugin) {
            $discountConfiguratorTransfer = $discountPostSavePlugin->postCreate($discountConfiguratorTransfer);
        }

        return $discountConfiguratorTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountConfiguratorTransfer
     */
    protected function executePostUpdatePlugins(DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        foreach ($this->discountPostUpdatePlugins as $discountPostUpdatePlugin) {
            $discountConfiguratorTransfer = $discountPostUpdatePlugin->postUpdate($discountConfiguratorTransfer);
        }
        return $discountConfiguratorTransfer;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     * @param \Generated\Shared\Transfer\DiscountConfiguratorTransfer $discountConfiguratorTransfer
     *
     * @return void
     */
    protected function saveDiscountMoneyValues(SpyDiscount $discountEntity, DiscountConfiguratorTransfer $discountConfiguratorTransfer)
    {
        $discountCalculatorTransfer = $discountConfiguratorTransfer->getDiscountCalculator();
        if ($discountCalculatorTransfer->getCalculatorPlugin() !== DiscountDependencyProvider::PLUGIN_CALCULATOR_FIXED) {
            $this->deleteDiscountMoneyValues($discountEntity);

            return;
        }

        foreach ($discountCalculatorTransfer->getMoneyValueCollection() as $moneyValueTransfer) {
            $discountAmountEntity = $this->discountQueryContainer
                ->queryDiscountAmountById($moneyValueTransfer->getIdEntity())
                ->findOneOrCreate();

            $discountAmountEntity->fromArray($moneyValueTransfer->modifiedToArray());
            $discountAmountEntity->setFkDiscount($discountEntity->getIdDiscount());
            $discountAmountEntity->save();
        }
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return void
     */
    protected function deleteDiscountMoneyValues(SpyDiscount $discountEntity): void
    {
        /** @var \Orm\Zed\Discount\Persistence\SpyDiscountAmount[]|null $discountAmountEntities */
        $discountAmountEntities = $discountEntity->getDiscountAmounts();
        if (!$discountAmountEntities) {
            return;
        }

        foreach ($discountAmountEntities as $discountAmountEntity) {
            $discountAmountEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     * @param int $idDiscount
     *
     * @return void
     */
    protected function saveDiscountStoreRelation(StoreRelationTransfer $storeRelationTransfer, $idDiscount)
    {
        $storeRelationTransfer->setIdEntity($idDiscount);

        $this->discountStoreRelationWriter->update($storeRelationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return void
     */
    protected function updateDiscountStoreRelation(StoreRelationTransfer $storeRelationTransfer)
    {
        $this->discountStoreRelationWriter->update($storeRelationTransfer);
    }
}
