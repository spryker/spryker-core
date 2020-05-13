<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnPageSearch\Business\Writer;

use Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchEntityManagerInterface;
use Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchRepositoryInterface;

class ReturnReasonSearchWriter
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
     * @var \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\SalesReturnPageSearch\Dependency\Facade\SalesReturnPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchRepositoryInterface $repository
     * @param \Spryker\Zed\SalesReturnPageSearch\Persistence\SalesReturnPageSearchEntityManagerInterface $entityManager
     */
    public function __construct(
        SalesReturnPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        SalesReturnPageSearchRepositoryInterface $repository,
        SalesReturnPageSearchEntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByReturnReasonEvents(array $eventTransfers): void
    {
        $returnReasonsIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $this->entityManager->deleteReturnReasonSearchByReturnReasonIds($returnReasonsIds);
    }
}
