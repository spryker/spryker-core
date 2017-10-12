<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Dependency\Plugin;

interface UsersTableExpanderPluginInterface
{
    /**
     * @api
     *
     * @param array $user
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function getActionButtonDefinitions(array $user);
}
