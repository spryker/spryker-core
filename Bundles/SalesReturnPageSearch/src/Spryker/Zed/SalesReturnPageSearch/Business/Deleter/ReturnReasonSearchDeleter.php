<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Business\Deleter;

use Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchEntityManagerInterface;

class ReturnReasonSearchDeleter implements ReturnReasonSearchDeleterInterface
{
    /**
     * @var \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @param \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchEntityManagerInterface $entityManager
     */
    public function __construct(
        SalesReturnPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        SalesReturnPageSearchEntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function deleteCollectionByReturnReasonEvents(array $eventTransfers): void
    {
        $returnReasonIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $this->entityManager->deleteReturnReasonSearchByReturnReasonIds($returnReasonIds);
    }
}
