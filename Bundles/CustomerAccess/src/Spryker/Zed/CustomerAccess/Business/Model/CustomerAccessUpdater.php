<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business\Model;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Zed\CustomerAccess\Persistence\CustomerAccessQueryContainerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CustomerAccessUpdater implements CustomerAccessUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessQueryContainerInterface
     */
    protected $customerAccessQueryContainer;

    /**
     * @param \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessQueryContainerInterface $customerAccessQueryContainer
     */
    public function __construct(CustomerAccessQueryContainerInterface $customerAccessQueryContainer)
    {
        $this->customerAccessQueryContainer = $customerAccessQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return void
     */
    public function updateOnlyContentTypesToAccessible(CustomerAccessTransfer $customerAccessTransfer)
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($customerAccessTransfer) {
            $this->setAllContentTypesToInaccessible();
            $this->setContentTypesToAccessible($customerAccessTransfer);
        });
    }

    /**
     * @return void
     */
    protected function setAllContentTypesToInaccessible()
    {
        $customerAccessEntities = $this->customerAccessQueryContainer->queryCustomerAccess()->find();

        foreach ($customerAccessEntities as $customerAccessEntity) {
            $customerAccessEntity->setHasAccess(false);
            $customerAccessEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return void
     */
    protected function setContentTypesToAccessible(CustomerAccessTransfer $customerAccessTransfer)
    {
        foreach ($customerAccessTransfer->getContentTypeAccess() as $contentTypeAccess) {
            $customerAccessEntity = $this->customerAccessQueryContainer
                ->queryCustomerAccess()
                ->filterByContentType($contentTypeAccess->getContentType())
                ->findOne();

            $customerAccessEntity->setHasAccess(true);
            $customerAccessEntity->save();
        }
    }
}
