<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Stock;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Shared\Stock\StockConfig as SharedStockConfig;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Stock\Business\StockProduct\StockProductUpdaterInterface;
use Spryker\Zed\Stock\Dependency\Facade\StockToEventFacadeInterface;
use Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface;
use Spryker\Zed\Stock\Persistence\StockEntityManagerInterface;
use Spryker\Zed\Stock\Persistence\StockRepositoryInterface;
use Spryker\Zed\Stock\StockConfig;

class StockUpdater implements StockUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const TOUCH_STOCK_TYPE = 'stock-type';

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockEntityManagerInterface
     */
    protected $stockEntityManager;

    /**
     * @var \Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Stock\Business\Stock\StockStoreRelationshipUpdaterInterface
     */
    protected $stockStoreRelationshipUpdater;

    /**
     * @var \Spryker\Zed\Stock\Business\StockProduct\StockProductUpdaterInterface
     */
    protected $stockProductUpdater;

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockRepositoryInterface
     */
    protected StockRepositoryInterface $stockRepository;

    /**
     * @var array<\Spryker\Zed\StockExtension\Dependency\Plugin\StockPostUpdatePluginInterface>
     */
    protected $stockPostUpdatePlugins;

    /**
     * @var \Spryker\Zed\Stock\StockConfig
     */
    protected StockConfig $stockConfig;

    /**
     * @var \Spryker\Zed\Stock\Dependency\Facade\StockToEventFacadeInterface
     */
    protected StockToEventFacadeInterface $eventFacade;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockEntityManagerInterface $stockEntityManager
     * @param \Spryker\Zed\Stock\Persistence\StockRepositoryInterface $stockRepository
     * @param \Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface $touchFacade
     * @param \Spryker\Zed\Stock\Business\Stock\StockStoreRelationshipUpdaterInterface $stockStoreRelationshipUpdater
     * @param \Spryker\Zed\Stock\Business\StockProduct\StockProductUpdaterInterface $stockProductUpdater
     * @param \Spryker\Zed\Stock\StockConfig $stockConfig
     * @param array<\Spryker\Zed\StockExtension\Dependency\Plugin\StockPostUpdatePluginInterface> $stockPostUpdatePlugins
     * @param \Spryker\Zed\Stock\Dependency\Facade\StockToEventFacadeInterface $eventFacade
     */
    public function __construct(
        StockEntityManagerInterface $stockEntityManager,
        StockRepositoryInterface $stockRepository,
        StockToTouchInterface $touchFacade,
        StockStoreRelationshipUpdaterInterface $stockStoreRelationshipUpdater,
        StockProductUpdaterInterface $stockProductUpdater,
        StockConfig $stockConfig,
        array $stockPostUpdatePlugins,
        StockToEventFacadeInterface $eventFacade
    ) {
        $this->stockEntityManager = $stockEntityManager;
        $this->stockRepository = $stockRepository;
        $this->touchFacade = $touchFacade;
        $this->stockStoreRelationshipUpdater = $stockStoreRelationshipUpdater;
        $this->stockProductUpdater = $stockProductUpdater;
        $this->stockPostUpdatePlugins = $stockPostUpdatePlugins;
        $this->stockConfig = $stockConfig;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function updateStock(StockTransfer $stockTransfer): StockResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\StockResponseTransfer $stockResponseTransfer */
        $stockResponseTransfer = $this->getTransactionHandler()->handleTransaction(function () use ($stockTransfer) {
            return $this->executeUpdateStockTransaction($stockTransfer);
        });

        $updatedStockTransfer = $stockResponseTransfer->getStock();
        if (!$updatedStockTransfer || !$stockResponseTransfer->getIsSuccessful()) {
            return $stockResponseTransfer;
        }

        if ($stockTransfer->getShouldUpdateStockRelationsAsync()) {
            $eventEntityTransfer = (new EventEntityTransfer())
                ->setId($updatedStockTransfer->getIdStock())
                ->setAdditionalValues($updatedStockTransfer->toArray());

            $this->eventFacade->trigger(SharedStockConfig::STOCK_POST_UPDATE_STOCK_RELATIONS, $eventEntityTransfer);
        }

        return $stockResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    protected function executeUpdateStockTransaction(StockTransfer $stockTransfer): StockResponseTransfer
    {
        $existingStockTransfer = $this->findExistingStockRecord($stockTransfer);
        if ($existingStockTransfer === null) {
            return (new StockResponseTransfer())
                ->setIsSuccessful(false);
        }

        $stockTransfer = $this->stockEntityManager->saveStock($stockTransfer);

        $isConditionalStockUpdateApplied = $this->stockConfig->isConditionalStockUpdateApplied();
        if (
            !$isConditionalStockUpdateApplied || /** @phpstan-ignore-next-line */
            ($isConditionalStockUpdateApplied && $this->hasStoreRelationChanged($existingStockTransfer, $stockTransfer))
        ) {
            $this->stockStoreRelationshipUpdater->updateStockStoreRelationshipsForStock(
                $stockTransfer->getIdStock(),
                $stockTransfer->getStoreRelation(),
            );
        }

        if (
            !$isConditionalStockUpdateApplied || /** @phpstan-ignore-next-line */
            ($isConditionalStockUpdateApplied && !$this->isOnlyNameChanged($existingStockTransfer, $stockTransfer))
        ) {
            $this->insertActiveTouchRecordStockType($stockTransfer);

            if (!$stockTransfer->getShouldUpdateStockRelationsAsync()) {
                $this->stockProductUpdater->updateStockProductsRelatedToStock($stockTransfer);
            }
        }

        return $this->executeStockPostUpdatePlugins($stockTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return void
     */
    protected function insertActiveTouchRecordStockType(StockTransfer $stockTransfer): void
    {
        $this->touchFacade->touchActive(
            static::TOUCH_STOCK_TYPE,
            $stockTransfer->getIdStock(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    protected function executeStockPostUpdatePlugins(StockTransfer $stockTransfer): StockResponseTransfer
    {
        foreach ($this->stockPostUpdatePlugins as $stockPostUpdatePlugin) {
            $stockResponseTransfer = $stockPostUpdatePlugin->postUpdate($stockTransfer);

            if (!$stockResponseTransfer->getIsSuccessful()) {
                return $stockResponseTransfer;
            }

            $stockTransfer = $stockResponseTransfer->getStock();
        }

        return (new StockResponseTransfer())
            ->setStock($stockTransfer)
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    protected function findExistingStockRecord(StockTransfer $stockTransfer): ?StockTransfer
    {
        $existingStockTransfer = $this->stockRepository->findStockById((int)$stockTransfer->getIdStockOrFail());

        if ($existingStockTransfer === null) {
            return null;
        }

        $storeRelation = $this->stockRepository->getStoreRelationByIdStock((int)$existingStockTransfer->getIdStockOrFail());
        $existingStockTransfer->setStoreRelation($storeRelation);

        return $existingStockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $existingStockTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $newStockTransfer
     *
     * @return list<string>
     */
    protected function getChangedProperties(StockTransfer $existingStockTransfer, StockTransfer $newStockTransfer): array
    {
        $changedProperties = [];

        $existingStockData = $existingStockTransfer->toArray(true, true);
        $newStockData = $newStockTransfer->toArray(true, true);

        foreach ($existingStockData as $property => $value) {
            // Not strict comparison because integer values could be string when set from the request.
            if ($value == $newStockData[$property]) {
                continue;
            }

            if ($property === StockTransfer::STORE_RELATION) {
                $existingStoreRelation = $existingStockTransfer->getStoreRelation();
                $newStoreRelation = $newStockTransfer->getStoreRelation();

                if (
                    $existingStoreRelation !== null && $newStoreRelation !== null
                    && $existingStoreRelation->getIdStores() !== $newStoreRelation->getIdStores()
                ) {
                    $changedProperties[] = $property;
                }

                continue;
            }

            $changedProperties[] = $property;
        }

        return $changedProperties;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $existingStockTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $newStockTransfer
     *
     * @return bool
     */
    protected function isOnlyNameChanged(StockTransfer $existingStockTransfer, StockTransfer $newStockTransfer): bool
    {
        $changedProperties = $this->getChangedProperties($existingStockTransfer, $newStockTransfer);

        return count($changedProperties) === 1 && in_array(StockTransfer::NAME, $changedProperties, true);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $existingStockTransfer
     * @param \Generated\Shared\Transfer\StockTransfer $newStockTransfer
     *
     * @return bool
     */
    protected function hasStoreRelationChanged(StockTransfer $existingStockTransfer, StockTransfer $newStockTransfer): bool
    {
        $changedProperties = $this->getChangedProperties($existingStockTransfer, $newStockTransfer);

        return in_array(StockTransfer::STORE_RELATION, $changedProperties, true);
    }
}
