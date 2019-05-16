<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShareExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;

interface CartShareOptionPluginInterface
{
    /**
     * Specification:
     * - Returns share option key.
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string;

    /**
     * Specification:
     * - Returns share option group name.
     *
     * @api
     *
     * @return string
     */
    public function getShareOptionGroup(): string;

    /**
     * Specification:
     * - Checks if share option is allowed for customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function isApplicable(CustomerTransfer $customerTransfer): bool;
}
