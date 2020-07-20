<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Business\Deleter;

use Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchEntityManagerInterface;

class ReturnReasonSearchDeleter implements ReturnReasonSearchDeleterInterface
{
    /**
     * @var \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @param \Spryker\Zed\SalesReturnSearch\Dependency\Facade\SalesReturnSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\SalesReturnSearch\Persistence\SalesReturnSearchEntityManagerInterface $entityManager
     */
    public function __construct(
        SalesReturnSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        SalesReturnSearchEntityManagerInterface $entityManager
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
