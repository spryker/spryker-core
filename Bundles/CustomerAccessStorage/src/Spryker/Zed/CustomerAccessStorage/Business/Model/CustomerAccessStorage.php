<?php

namespace Spryker\Zed\CustomerAccessStorage\Business\Model;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Orm\Zed\AvailabilityStorage\Persistence\SpyAvailabilityStorage;
use Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorage;
use Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageQueryContainerInterface;

class CustomerAccessStorage implements CustomerAccessStorageInterface
{
    /**
     * @var \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageQueryContainerInterface
     */
    protected $customerAccessQueryContainer;

    /**
     * @param \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageQueryContainerInterface $customerAccessQueryContainer
     */
    public function __construct(CustomerAccessStorageQueryContainerInterface $customerAccessQueryContainer)
    {
        $this->customerAccessQueryContainer = $customerAccessQueryContainer;
    }

    /**
     * @return void
     */
    public function publish()
    {
        $customerAccessEntities = $this->customerAccessQueryContainer->queryCustomerAccess()->find();
        $customerAccessStorageEntity = $this->customerAccessQueryContainer->queryCustomerAccessStorage()->findOne();

        $this->storeData($customerAccessEntities, $customerAccessStorageEntity);
    }

    /**
     * @param \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess[] $customerAccessEntities
     * @param \Orm\Zed\CustomerAccessStorage\Persistence\SpyUnauthenticatedCustomerAccessStorage | null $customerAccessStorageEntity
     *
     * @return void
     */
    protected function storeData($customerAccessEntities, $customerAccessStorageEntity = null)
    {
        if(is_null($customerAccessStorageEntity)) {
            $customerAccessStorageEntity = new SpyUnauthenticatedCustomerAccessStorage();
        }

        $customerAccessTransfer = $this->fillCustomerAccessTransferFromEntities($customerAccessEntities);
        $customerAccessStorageEntity->setData($customerAccessTransfer->toArray());
        $customerAccessStorageEntity->save();
    }

    /**
     * @param \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess[] $customerAccessEntities
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    protected function fillCustomerAccessTransferFromEntities($customerAccessEntities)
    {
        $customerAccessTransfer = new CustomerAccessTransfer();

        foreach($customerAccessEntities as $customerAccess) {
            $customerAccessTransfer->addContentTypeAccess(
                (new ContentTypeAccessTransfer())
                    ->setContentType($customerAccess->getContentType())
                    ->setCanAccess($customerAccess->getCanAccess())
            );
        }

        return $customerAccessTransfer;
    }
}
