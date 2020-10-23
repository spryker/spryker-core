<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Business\Deleter;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Shared\MerchantSearch\MerchantSearchConfig;
use Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface;
use Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface;

class MerchantSearchDeleter implements MerchantSearchDeleterInterface
{
    /**
     * @var \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @param \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantSearch\Persistence\MerchantSearchEntityManagerInterface $entityManager
     * @param \Spryker\Zed\MerchantSearch\Dependency\Facade\MerchantSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     */
    public function __construct(
        MerchantSearchToMerchantFacadeInterface $merchantFacade,
        MerchantSearchEntityManagerInterface $entityManager,
        MerchantSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->entityManager = $entityManager;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByMerchantEvents(array $eventTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
        if (!$merchantIds) {
            return;
        }
        $merchantCollectionTransfer = $this->merchantFacade->get(
            (new MerchantCriteriaTransfer())
                ->setMerchantIds($merchantIds)
                ->setIsActive(true)
                ->setStatus(MerchantSearchConfig::MERCHANT_STATUS_APPROVED)
        );
        $activeMerchantIds = array_map(function (MerchantTransfer $merchant) {
            return $merchant->getIdMerchant();
        }, $merchantCollectionTransfer->getMerchants()->getArrayCopy());

        $merchantsToDelete = array_diff($merchantIds, $activeMerchantIds);

        if ($merchantsToDelete) {
            return;
        }

        $this->entityManager->deleteMerchantSearchByMerchantIds($merchantsToDelete);
    }
}
