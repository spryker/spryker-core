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
    protected $merchantMapper;

    /**
     * @var \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantSearch\Business\Mapper\MerchantSearchMapperInterface $merchantMapper
     * @param \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface $entityManager
     */
    public function __construct(
        MerchantSearchToMerchantFacadeInterface $merchantFacade,
        MerchantSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantSearchMapperInterface $merchantMapper,
        MerchantSearchEntityManagerInterface $entityManager
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantMapper = $merchantMapper;
        $this->entityManager = $entityManager;
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
        $merchantSearchCollectionTransfer = $this->merchantMapper
            ->mapMerchantCollectionTransferToMerchantSearchCollectionTransfer(
                $merchantCollectionTransfer,
                new MerchantSearchCollectionTransfer(),
            );

        $this->writeCollection($merchantSearchCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     *
     * @return void
     */
    protected function writeCollection(MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer): void
    {
        foreach ($merchantSearchCollectionTransfer->getMerchantSearches() as $merchantSearchTransfer) {
            $this->entityManager->saveMerchantSearch($merchantSearchTransfer);
        }
    }
}
