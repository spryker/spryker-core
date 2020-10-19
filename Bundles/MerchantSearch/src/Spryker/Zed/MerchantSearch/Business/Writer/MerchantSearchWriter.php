<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business\Writer;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Generated\Shared\Transfer\MerchantSearchTransfer;
use Spryker\Zed\MerchantSearch\Business\Mapper\MerchantSearchMapperInterface;
use Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface;
use Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface;
use Spryker\Zed\MerchantSearch\Persistence\MerchantSearchRepositoryInterface;

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
     * @var \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantSearch\Business\Mapper\MerchantSearchMapperInterface $merchantMapper
     * @param \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface $entityManager
     * @param \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchRepositoryInterface $repository
     */
    public function __construct(
        MerchantSearchToMerchantFacadeInterface $merchantFacade,
        MerchantSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantSearchMapperInterface $merchantMapper,
        MerchantSearchEntityManagerInterface $entityManager,
        MerchantSearchRepositoryInterface $repository
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantMapper = $merchantMapper;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
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
            (new MerchantCriteriaTransfer())->setMerchantIds($merchantIds)
        );

        if (!$merchantCollectionTransfer->getMerchants()->count()) {
            $this->entityManager->deleteMerchantSearchByMerchantIds($merchantIds);

            return;
        }
        $merchantSearchCollectionTransfer = $this->getMerchantSearchTransfersByMerchantTransfers($merchantCollectionTransfer);
        $this->writeCollection($merchantSearchCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantSearchCollectionTransfer
     */
    protected function getMerchantSearchTransfersByMerchantTransfers(
        MerchantCollectionTransfer $merchantCollectionTransfer
    ): MerchantSearchCollectionTransfer {
        $merchantSearchCollectionTransfer = new MerchantSearchCollectionTransfer();

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantSearchTransfer = new MerchantSearchTransfer();
            $this->merchantMapper->mapMerchantTransferToMerchantSearchTransfer($merchantTransfer, $merchantSearchTransfer);
            $merchantSearchCollectionTransfer->addMerchantSearch($merchantSearchTransfer);
        }

        return $merchantSearchCollectionTransfer;
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
