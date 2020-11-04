<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business\Writer;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Spryker\Shared\MerchantSearch\MerchantSearchConfig;
use Spryker\Zed\MerchantSearch\Business\Mapper\MerchantSearchMapperInterface;
use Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface;
use Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface;

class MerchantSearchWriter implements MerchantSearchWriterInterface
{
    /**
     * @uses \Orm\Zed\MerchantCategory\Persistence\Map\SpyMerchantCategoryTableMap::COL_FK_MERCHANT
     */
    protected const FK_CATEGORY_MERCHANT = 'spy_merchant_category.fk_merchant';

    /**
     * @var \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantSearch\Business\Mapper\MerchantSearchMapperInterface
     */
    protected $merchantSearchMapper;

    /**
     * @var \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface
     */
    protected $merchantSearchEntityManager;

    /**
     * @var \Spryker\Zed\MerchantSearchExtension\Dependency\Plugin\MerchantSearchDataExpanderPluginInterface[]
     */
    protected $merchantSearchDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantSearch\Business\Mapper\MerchantSearchMapperInterface $merchantSearchMapper
     * @param \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface $merchantSearchEntityManager
     * @param \Spryker\Zed\MerchantSearchExtension\Dependency\Plugin\MerchantSearchDataExpanderPluginInterface[] $merchantSearchDataExpanderPlugins
     */
    public function __construct(
        MerchantSearchToMerchantFacadeInterface $merchantFacade,
        MerchantSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantSearchMapperInterface $merchantSearchMapper,
        MerchantSearchEntityManagerInterface $merchantSearchEntityManager,
        array $merchantSearchDataExpanderPlugins
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantSearchMapper = $merchantSearchMapper;
        $this->merchantSearchEntityManager = $merchantSearchEntityManager;
        $this->merchantSearchDataExpanderPlugins = $merchantSearchDataExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantEvents(array $eventTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
        $this->writeCollectionByMerchantIds($merchantIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantCategoryEvents(array $eventTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventTransfers, static::FK_CATEGORY_MERCHANT);
        $this->writeCollectionByMerchantIds($merchantIds);
    }

    /**
     * @param int[] $merchantIds
     *
     * @return void
     */
    protected function writeCollectionByMerchantIds(array $merchantIds): void
    {
        if (!$merchantIds) {
            return;
        }

        $merchantCollectionTransfer = $this->merchantFacade->get(
            (new MerchantCriteriaTransfer())
                ->setMerchantIds($merchantIds)
                ->setIsActive(true)
                ->setStatus(MerchantSearchConfig::MERCHANT_STATUS_APPROVED)
        );

        if (!$merchantCollectionTransfer->getMerchants()->count()) {
            return;
        }

        $merchantSearchCollectionTransfer = $this->merchantSearchMapper->mapMerchantCollectionTransferToMerchantSearchCollectionTransfer(
            $merchantCollectionTransfer,
            new MerchantSearchCollectionTransfer()
        );

        $merchantSearchCollectionTransfer = $this->expandMerchantSearchData($merchantSearchCollectionTransfer);

        $this->writeCollection($merchantSearchCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     *
     * @return void
     */
    protected function writeCollection(MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer): void
    {
        $this->merchantSearchEntityManager->saveMerchantSearches($merchantSearchCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    protected function expandMerchantSearchData(MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer): MerchantSearchCollectionTransfer
    {
        foreach ($this->merchantSearchDataExpanderPlugins as $merchantSearchDataExpanderPlugin) {
            $merchantSearchCollectionTransfer = $merchantSearchDataExpanderPlugin->expand($merchantSearchCollectionTransfer);
        }

        return $merchantSearchCollectionTransfer;
    }
}
