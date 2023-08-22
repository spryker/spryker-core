<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UsersBackendApi;

use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserResourceCollectionTransfer;

interface UsersBackendApiResourceInterface
{
    /**
     * Specification:
     * - Retrieves multiple user resources by criteria.
     * - Returns the collection of user rest resources.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Glue\UsersBackendApi\UsersBackendApiResourceInterface::getUserResources()} instead.
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUserResourceCollection(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer;

    /**
     * Specification:
     * - Retrieves multiple user resources by criteria.
     * - Returns the collection of user rest resources.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Glue\UsersBackendApi\UsersBackendApiResourceInterface::getUsersResources()} instead.
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUserResources(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer;

    /**
     * Specification:
     * - Retrieves multiple user resources by criteria.
     * - Returns the collection of user rest resources.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserResourceCollectionTransfer
     */
    public function getUsersResources(UserCriteriaTransfer $userCriteriaTransfer): UserResourceCollectionTransfer;
}
