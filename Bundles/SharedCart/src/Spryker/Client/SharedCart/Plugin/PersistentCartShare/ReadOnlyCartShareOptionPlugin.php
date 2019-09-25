<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Plugin\PersistentCartShare;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface;
use Spryker\Shared\SharedCart\SharedCartConfig as SharedSharedCartConfig;

/**
 * @method \Spryker\Client\SharedCart\SharedCartFactory getFactory()
 */
class ReadOnlyCartShareOptionPlugin extends AbstractPlugin implements CartShareOptionPluginInterface
{
    protected const SHARE_OPTION_GROUP_INTERNAL = 'internal';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getShareOptionKey(): string
    {
        return SharedSharedCartConfig::PERMISSION_GROUP_READ_ONLY;
    }

    /**
     * {@inheritDoc}
     * - Returns true if customer is provided and it is a company user.
     * - Returns false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return bool
     */
    public function isApplicable(?CustomerTransfer $customerTransfer): bool
    {
        if (!$customerTransfer) {
            return false;
        }

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();

        return $companyUserTransfer && $companyUserTransfer->getIdCompanyUser();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getShareOptionGroup(): string
    {
        return static::SHARE_OPTION_GROUP_INTERNAL;
    }
}
