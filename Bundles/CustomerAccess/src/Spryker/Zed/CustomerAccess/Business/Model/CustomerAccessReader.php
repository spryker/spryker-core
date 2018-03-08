<?php

namespace Spryker\Zed\CustomerAccess\Business\Model;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Zed\CustomerAccess\CustomerAccessConfig;
use Spryker\Zed\CustomerAccess\Persistence\CustomerAccessQueryContainerInterface;

class CustomerAccessReader implements CustomerAccessReaderInterface
{
    /**
     * @var \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessQueryContainerInterface
     */
    protected $customerAccessQueryContainer;

    /**
     * @var \Spryker\Zed\CustomerAccess\CustomerAccessConfig
     */
    protected $customerAccessConfig;

    /**
     * @param \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessQueryContainerInterface $customerAccessQueryContainer
     */
    public function __construct(CustomerAccessQueryContainerInterface $customerAccessQueryContainer, CustomerAccessConfig $customerAccessConfig)
    {
        $this->customerAccessQueryContainer = $customerAccessQueryContainer;
        $this->customerAccessConfig = $customerAccessConfig;
    }

    /**
     * @param string $contentType
     *
     * @return \Generated\Shared\Transfer\ContentTypeAccessTransfer|null
     */
    public function findCustomerAccessByContentType($contentType)
    {
        $customerAccessEntity = $this->customerAccessQueryContainer
            ->queryCustomerAccess()
            ->filterByContentType($contentType)
            ->findOne();

        if(!$customerAccessEntity) {
            return null;
        }

        return (new ContentTypeAccessTransfer())->fromArray($customerAccessEntity->toArray());
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function findUnauthenticatedCustomerAccess()
    {
        $unauthenticatedCustomerAccess = $this->customerAccessQueryContainer
            ->queryCustomerAccess()
            ->find();

        $defaultContentTypes = $this->customerAccessConfig->getDefaultContentTypes();
        $defaultAccess = $this->customerAccessConfig->getDefaultContentTypeAccess();

        $combinedContentAccess = [];

        foreach($defaultContentTypes as $contentType) {
            $combinedContentAccess[$contentType] = $defaultAccess;
        }

        foreach($unauthenticatedCustomerAccess as $customerAccess) {
            $combinedContentAccess[$customerAccess->getContentType()] = $customerAccess->getCanAccess();
        }

        $customerAccessTransfer = new CustomerAccessTransfer();

        foreach($combinedContentAccess as $contentType => $contentAccess) {
            $customerAccessTransfer->addContentTypeAccess(
                (new ContentTypeAccessTransfer())->setContentType($contentType)->setCanAccess($contentAccess)
            );
        }

        return $customerAccessTransfer;
    }
}