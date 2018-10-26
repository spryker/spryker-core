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

/**
 * @method \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessPersistenceFactory getFactory()
 */
class CustomerAccessEntityManager extends AbstractEntityManager implements CustomerAccessEntityManagerInterface
{
    /**
     * @param string $contentType
     * @param bool $isRestricted
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function createCustomerAccess(string $contentType, bool $isRestricted): CustomerAccessTransfer
    {
        $customerAccessEntity = $this->getFactory()->customerAccessQuery()
            ->filterByContentType($contentType)
            ->findOneOrCreate();

        $customerAccessEntity->setIsRestricted($isRestricted);
        $customerAccessEntity->save();

        return $this->getFactory()
            ->createCustomerAccessMapper()
            ->mapEntityToCustomerAccessTransfer($customerAccessEntity, new CustomerAccessTransfer());
    }

    /**
     * @return void
     */
    public function setAllContentTypesToAccessible(): void
    {
        $customerAccessEntities = $this->getFactory()->customerAccessQuery()->find();

        foreach ($customerAccessEntities as $customerAccessEntity) {
            $customerAccessEntity->setIsRestricted(false);
            $customerAccessEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function setContentTypesToInaccessible(CustomerAccessTransfer $customerAccessTransfer): CustomerAccessTransfer
    {
        $updatedContentTypeAccessCollection = new ArrayObject();
        foreach ($customerAccessTransfer->getContentTypeAccess() as $contentTypeAccess) {
            $customerAccessEntity = $this->getCustomerAccessEntityByContentType($contentTypeAccess);
            $customerAccessEntity = $customerAccessEntity ? $customerAccessEntity : $this->createCustomerAccessEntity($contentTypeAccess);
            $customerAccessEntity->setIsRestricted(true);
            $customerAccessEntity->save();
            $updatedContentTypeAccessCollection->append(
                $this->getFactory()
                    ->createCustomerAccessMapper()
                    ->mapCustomerAccessEntityToContentTypeAccessTransfer($customerAccessEntity, new ContentTypeAccessTransfer())
            );
        }
        $customerAccessTransfer->setContentTypeAccess($updatedContentTypeAccessCollection);

        return $customerAccessTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeAccessTransfer $contentTypeAccessTransfer
     *
     * @return \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess|null
     */
    protected function getCustomerAccessEntityByContentType(ContentTypeAccessTransfer $contentTypeAccessTransfer): ?SpyUnauthenticatedCustomerAccess
    {
        return $this->getFactory()
            ->customerAccessQuery()
            ->filterByContentType($contentTypeAccessTransfer->getContentType())
            ->findOne();
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTypeAccessTransfer $contentTypeAccessTransfer
     *
     * @return \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess
     */
    protected function createCustomerAccessEntity(ContentTypeAccessTransfer $contentTypeAccessTransfer): SpyUnauthenticatedCustomerAccess
    {
        $spyCustomerAccess = new SpyUnauthenticatedCustomerAccess();
        $spyCustomerAccess->setContentType($contentTypeAccessTransfer->getContentType());

        return $spyCustomerAccess;
    }
}
