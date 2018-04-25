<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Generated\Shared\Transfer\SpyUnauthenticatedCustomerAccessEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

/**
 * @method \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessPersistenceFactory getFactory()
 */
class CustomerAccessEntityManager extends AbstractEntityManager implements CustomerAccessEntityManagerInterface
{
    use TransactionTrait;

    /**
     * @param string $contentType
     * @param bool $hasAccess
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function createCustomerAccess($contentType, $hasAccess): CustomerAccessTransfer
    {
        $customerAccessEntity = $this->save(
            (new SpyUnauthenticatedCustomerAccessEntityTransfer())->setHasAccess($hasAccess)->setContentType($contentType)
        );

        return (new CustomerAccessTransfer())->addContentTypeAccess(
            $this->getFactory()->createCustomerAccessMapper()->mapEntityToTransfer($customerAccessEntity)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function updateUnauthenticatedCustomerAccess(CustomerAccessTransfer $customerAccessTransfer)
    {
        $result = $this->getTransactionHandler()->handleTransaction(function () use ($customerAccessTransfer) {
            $this->setAllContentTypesToInaccessible();
            return $this->setContentTypesToAccessible($customerAccessTransfer);
        });

        return $result;
    }

    /**
     * @return void
     */
    protected function setAllContentTypesToInaccessible()
    {
        $customerAccessEntities = $this->getFactory()->createPropelCustomerAccessQuery()->find();

        foreach ($customerAccessEntities as $customerAccessEntity) {
            $customerAccessEntity->setHasAccess(false);
            $customerAccessEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    protected function setContentTypesToAccessible(CustomerAccessTransfer $customerAccessTransfer)
    {
        $updatedContentTypeAccessCollection = new ArrayObject();
        foreach ($customerAccessTransfer->getContentTypeAccess() as $contentTypeAccess) {
            $contentTypeAccessEntity = $this->getFactory()->createCustomerAccessMapper()->mapTransferToEntity($contentTypeAccess);
            $contentTypeAccessEntity->setHasAccess(true);
            $updatedContentTypeAccessCollection->append($this->save($contentTypeAccessEntity));
        }
        $customerAccessTransfer->setContentTypeAccess($updatedContentTypeAccessCollection);

        return $customerAccessTransfer;
    }
}
