<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\UserTransfer;

/**
 * Use this plugin to filter Symfony security authentication roles when logging in to Backoffice.
 */
interface UserRoleFilterPluginInterface
{
    /**
     * Specification:
     * - Filters array of Symfony security authentication roles.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param array<string> $roles
     *
     * @return array<string>
     */
    public function filter(UserTransfer $userTransfer, array $roles): array;
}
