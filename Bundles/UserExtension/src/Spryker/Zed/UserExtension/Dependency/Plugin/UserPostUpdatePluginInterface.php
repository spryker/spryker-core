<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserExtension\Dependency\Plugin;

use Generated\Shared\Transfer\UserCollectionResponseTransfer;

/**
 * Provides extension capabilities for actions that should be executed after a User is updated.
 */
interface UserPostUpdatePluginInterface
{
    /**
     * Specification:
     * - Executes after a User is updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserCollectionResponseTransfer $userCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionResponseTransfer
     */
    public function postUpdate(
        UserCollectionResponseTransfer $userCollectionResponseTransfer
    ): UserCollectionResponseTransfer;
}
