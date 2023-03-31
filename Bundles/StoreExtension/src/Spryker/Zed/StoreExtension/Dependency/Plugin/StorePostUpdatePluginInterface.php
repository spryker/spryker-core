<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreExtension\Dependency\Plugin;

use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * Provides extension capabilities for Store Module.
 *
 * Use this plugin when you need to run an action after Store is updated.
 */
interface StorePostUpdatePluginInterface
{
    /**
     * Specification:
     * - Plugin is triggered after store is updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function execute(StoreTransfer $storeTransfer): StoreResponseTransfer;
}
