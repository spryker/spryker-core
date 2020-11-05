<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Business\Publisher;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\MerchantCategoryCriteriaTransfer;
use Orm\Zed\MerchantCategory\Persistence\Map\SpyMerchantCategoryTableMap;
use Spryker\Zed\MerchantCategory\Dependency\Facade\MerchantCategoryToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantCategory\Dependency\Facade\MerchantCategoryToEventFacadeInterface;
use Spryker\Zed\MerchantCategory\Dependency\MerchantCategoryEvents;
use Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface;

class MerchantCategoryPublisher implements MerchantCategoryPublisherInterface
{
    /**
     * @var \Spryker\Zed\MerchantCategory\Dependency\Facade\MerchantCategoryToEventFacadeInterface
     */
    protected $eventFacade;

    /**
     * @var \Spryker\Zed\MerchantCategory\Dependency\Facade\MerchantCategoryToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface
     */
    protected $merchantCategoryRepository;

    /**
     * @param \Spryker\Zed\MerchantCategory\Dependency\Facade\MerchantCategoryToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\MerchantCategory\Dependency\Facade\MerchantCategoryToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryRepositoryInterface $merchantCategoryRepository
     */
    public function __construct(
        MerchantCategoryToEventFacadeInterface $eventFacade,
        MerchantCategoryToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantCategoryRepositoryInterface $merchantCategoryRepository
    ) {
        $this->eventFacade = $eventFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantCategoryRepository = $merchantCategoryRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function publishCategoryMerchantEventsByCategoryEvents(array $eventTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);
        $merchantCategoryCriteriaTransfer = new MerchantCategoryCriteriaTransfer();
        $merchantCategoryCriteriaTransfer->setCategoryIds($categoryIds);

        $merchantCategoryTransfers = $this->merchantCategoryRepository->get($merchantCategoryCriteriaTransfer);

        $eventTransfers = [];
        foreach ($merchantCategoryTransfers as $merchantCategoryTransfer) {
            $eventTransfers[] = (new EventEntityTransfer())
                    ->setId($merchantCategoryTransfer->getIdMerchantCategory())
                    ->setForeignKeys([SpyMerchantCategoryTableMap::COL_FK_MERCHANT => $merchantCategoryTransfer->getFkMerchant()]);
        }

        $this->eventFacade->triggerBulk(MerchantCategoryEvents::MERCHANT_CATEGORY_PUBLISH, $eventTransfers);
    }
}
