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
        $this->customerAccessQueryContainer->queryCustomerAccess()->update([
            'CanAccess' => false,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return void
     */
    protected function setContentTypesToAccessible(CustomerAccessTransfer $customerAccessTransfer)
    {
        $accessibleContent = [];

        foreach($customerAccessTransfer->getContentTypeAccess() as $contentTypeAccess) {
            $accessibleContent[] = $contentTypeAccess->getContentType();
        }

        $this->customerAccessQueryContainer->queryCustomerAccess()->filterByContentType_In($accessibleContent)->update([
            'CanAccess' => true,
        ]);
    }
}
