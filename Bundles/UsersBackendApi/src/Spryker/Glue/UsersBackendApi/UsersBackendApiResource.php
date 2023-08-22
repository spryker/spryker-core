<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;
use Spryker\Glue\Kernel\Backend\AbstractRestResource;

/**
 * @method \Spryker\Glue\UsersBackendApi\UsersBackendApiFactory getFactory()
 */
class UsersBackendApiResource extends AbstractRestResource implements UsersBackendApiResourceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Glue\UsersBackendApi\UsersBackendApiResource::getUserResources()} instead.
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUserResourceCollection(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer
    {
        return $this->getFactory()
            ->createUserResourceReader()
            ->getUserResourceCollection($userCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Glue\UsersBackendApi\UsersBackendApiResource::getUsersResources()} instead.
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUserResources(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer
    {
        return $this->getFactory()
            ->createUserResourceReader()
            ->getUserResources($userCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUsersResources(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer
    {
        return $this->getFactory()
            ->createUserResourceReader()
            ->getUsersResources($userCriteriaTransfer);
    }
}
