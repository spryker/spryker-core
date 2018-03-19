<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business\Model;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Zed\CustomerAccess\Persistence\CustomerAccessQueryContainerInterface;

class CustomerAccessReader implements CustomerAccessReaderInterface
{
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

        if (!$customerAccessEntity) {
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
            ->filterByCanAccess(true)
            ->find();

        return $this->fillCustomerAccessTransferFromEntities($unauthenticatedCustomerAccess);
    }

    /**
     * @return \Generated\Shared\Transfer\ContentTypeAccessTransfer[]
     */
    public function findAllContentTypes()
    {
        $unauthenticatedCustomerAccess = $this->customerAccessQueryContainer
            ->queryCustomerAccess()
            ->orderByIdUnauthenticatedCustomerAccess()
            ->find();

        return $this->fillCustomerAccessTransferFromEntities($unauthenticatedCustomerAccess)->getContentTypeAccess();
    }

    /**
     * @param \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess[] $customerAccessEntities
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    protected function fillCustomerAccessTransferFromEntities($customerAccessEntities)
    {
        $customerAccessTransfer = new CustomerAccessTransfer();

        foreach ($customerAccessEntities as $customerAccess) {
            $customerAccessTransfer->addContentTypeAccess(
                (new ContentTypeAccessTransfer())
                    ->setContentType($customerAccess->getContentType())
                    ->setCanAccess($customerAccess->getCanAccess())
            );
        }

        return $customerAccessTransfer;
    }
}
