<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Persistence;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessPersistenceFactory getFactory()
 */
class CustomerAccessRepository extends AbstractRepository implements CustomerAccessRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $contentType
     *
     * @return \Generated\Shared\Transfer\ContentTypeAccessTransfer|null
     */
    public function findCustomerAccessByContentType($contentType): ?ContentTypeAccessTransfer
    {
        $customerAccessEntity = $this->getFactory()
            ->createPropelCustomerAccessQuery()
            ->filterByContentType($contentType)
            ->findOne();

        if (!$customerAccessEntity) {
            return null;
        }

        return (new ContentTypeAccessTransfer())->fromArray($customerAccessEntity->toArray(), true);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getContentTypesWithUnauthenticatedCustomerAccess(): CustomerAccessTransfer
    {
        $unauthenticatedCustomerAccessEntity = $this->getFactory()
            ->createPropelCustomerAccessQuery()
            ->filterByHasAccess(true)
            ->find();

        return $this->fillCustomerAccessTransferFromEntities($unauthenticatedCustomerAccessEntity);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAllContentTypes(): CustomerAccessTransfer
    {
        $unauthenticatedCustomerAccessEntity = $this->getFactory()
            ->createPropelCustomerAccessQuery()
            ->orderByIdUnauthenticatedCustomerAccess()
            ->find();

        return $this->fillCustomerAccessTransferFromEntities($unauthenticatedCustomerAccessEntity);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $customerAccessEntities
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    protected function fillCustomerAccessTransferFromEntities(ObjectCollection $customerAccessEntities): CustomerAccessTransfer
    {
        $customerAccessTransfer = new CustomerAccessTransfer();

        foreach ($customerAccessEntities as $customerAccessEntity) {
            $customerAccessTransfer->addContentTypeAccess(
                (new ContentTypeAccessTransfer())->fromArray($customerAccessEntity->toArray(), true)
            );
        }

        return $customerAccessTransfer;
    }
}
