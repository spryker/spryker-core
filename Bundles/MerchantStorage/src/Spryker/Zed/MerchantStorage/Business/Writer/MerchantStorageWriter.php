<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Business\Writer;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantStorageProfileTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface;
use Spryker\Zed\MerchantStorage\Persistence\MerchantStorageEntityManagerInterface;

class MerchantStorageWriter implements MerchantStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageEntityManagerInterface
     */
    protected $merchantStorageEntityManager;

    /**
     * @param \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageEntityManagerInterface $merchantStorageEntityManager
     */
    public function __construct(
        MerchantStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantStorageToMerchantFacadeInterface $merchantFacade,
        MerchantStorageEntityManagerInterface $merchantStorageEntityManager
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantFacade = $merchantFacade;
        $this->merchantStorageEntityManager = $merchantStorageEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantEvents(array $eventTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        if (!$merchantIds) {
            return;
        }

        $this->writeCollectionByMerchantIds($merchantIds);
    }

    /**
     * @param string[] $merchantIds
     *
     * @return void
     */
    protected function writeCollectionByMerchantIds(array $merchantIds): void
    {
        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setMerchantIds($merchantIds);

        $merchantCollectionTransfer = $this->merchantFacade->find($merchantCriteriaFilterTransfer);

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $this->merchantStorageEntityManager->saveMerchantStorage(
                $this->mapMerchantTransferToStorageTransfer($merchantTransfer)
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer
     */
    protected function mapMerchantTransferToStorageTransfer(MerchantTransfer $merchantTransfer): MerchantStorageTransfer
    {
        $merchantStorageTransfer = (new MerchantStorageTransfer())->fromArray($merchantTransfer->modifiedToArray(), true);
        $merchantStorageProfileTransfer = (new MerchantStorageProfileTransfer())->fromArray($merchantTransfer->getMerchantProfile()->modifiedToArray(), true);

        $merchantStorageTransfer->setMerchantStorageProfile($merchantStorageProfileTransfer);

        return $merchantStorageTransfer;
    }
}
