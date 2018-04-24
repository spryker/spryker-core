<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Persistence;

use Generated\Shared\Transfer\CustomerAccessTransfer;
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
    public function findCustomerAccessByContentType($contentType)
    {
        $customerAccessEntity = $this->buildQueryFromCriteria(
            $this->getFactory()
                ->createPropelCustomerAccessQuery()
                ->filterByContentType($contentType)
        )->findOne();

        if (!$customerAccessEntity) {
            return null;
        }

        return $this->getFactory()->createCustomerAccessMapper()->mapEntityToTransfer($customerAccessEntity);
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
        $unauthenticatedCustomerAccess = $this->buildQueryFromCriteria(
            $this->getFactory()
                ->createPropelCustomerAccessQuery()
                ->filterByHasAccess(true)
        )->find();

        return $this->fillCustomerAccessTransferFromEntities($unauthenticatedCustomerAccess);
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
        $unauthenticatedCustomerAccess = $this->buildQueryFromCriteria(
            $this->getFactory()
                ->createPropelCustomerAccessQuery()
                ->orderByIdUnauthenticatedCustomerAccess()
        )->find();

        return $this->fillCustomerAccessTransferFromEntities($unauthenticatedCustomerAccess);
    }

    /**
     * @param \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess[] $customerAccessEntities
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    protected function fillCustomerAccessTransferFromEntities($customerAccessEntities): CustomerAccessTransfer
    {
        $customerAccessTransfer = new CustomerAccessTransfer();

        foreach ($customerAccessEntities as $customerAccess) {
            $customerAccessTransfer->addContentTypeAccess(
                $this->getFactory()->createCustomerAccessMapper()->mapEntityToTransfer($customerAccess)
            );
        }

        return $customerAccessTransfer;
    }
}