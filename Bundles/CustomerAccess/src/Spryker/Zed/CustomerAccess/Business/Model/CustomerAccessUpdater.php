<?php

namespace Spryker\Zed\CustomerAccess\Business\Model;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Orm\Zed\CustomerAccess\Persistence\Map\SpyUnauthenticatedCustomerAccessTableMap;
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

        foreach($customerAccessEntities as $customerAccessEntity) {
            $customerAccessEntity->setCanAccess(false);
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
        foreach($customerAccessTransfer->getContentTypeAccess() as $contentTypeAccess) {
            $customerAccessEntity = $this->customerAccessQueryContainer
                ->queryCustomerAccess()
                ->filterByContentType($contentTypeAccess->getContentType())
                ->findOne();

            $customerAccessEntity->setCanAccess(true);
            $customerAccessEntity->save();
        }
    }
}
