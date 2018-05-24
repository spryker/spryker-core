<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess;
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
        $customerAccessEntity = $this->getFactory()->createPropelCustomerAccessQuery()
            ->filterByContentType($contentType)
            ->findOneOrCreate();

        $customerAccessEntity->setHasAccess($hasAccess);
        $customerAccessEntity->save();

        return (new CustomerAccessTransfer())->addContentTypeAccess(
            (new ContentTypeAccessTransfer())->fromArray($customerAccessEntity->toArray(), true)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function updateUnauthenticatedCustomerAccess(CustomerAccessTransfer $customerAccessTransfer): CustomerAccessTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($customerAccessTransfer) {
            $this->setAllContentTypesToInaccessible();
            return $this->setContentTypesToAccessible($customerAccessTransfer);
        });
    }

    /**
     * @return void
     */
    protected function setAllContentTypesToInaccessible(): void
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
    protected function setContentTypesToAccessible(CustomerAccessTransfer $customerAccessTransfer): CustomerAccessTransfer
    {
        $updatedContentTypeAccessCollection = new ArrayObject();
        foreach ($customerAccessTransfer->getContentTypeAccess() as $contentTypeAccess) {
            $contentTypeAccessEntity = $this->getContentTypeAccessEntity($contentTypeAccess);
            $contentTypeAccessEntity->setHasAccess(true);
            $contentTypeAccessEntity->save();

            $updatedContentTypeAccessCollection->append(
                (new ContentTypeAccessTransfer())->fromArray($contentTypeAccessEntity->toArray(), true)
            );
        }
        $customerAccessTransfer->setContentTypeAccess($updatedContentTypeAccessCollection);

        return $customerAccessTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeAccessTransfer $contentTypeAccessTransfer
     *
     * @return \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess
     */
    protected function getContentTypeAccessEntity(ContentTypeAccessTransfer $contentTypeAccessTransfer): SpyUnauthenticatedCustomerAccess
    {
        return $this->getFactory()->createPropelCustomerAccessQuery()
            ->filterByContentType($contentTypeAccessTransfer->getContentType())
            ->findOneOrCreate();
    }
}
