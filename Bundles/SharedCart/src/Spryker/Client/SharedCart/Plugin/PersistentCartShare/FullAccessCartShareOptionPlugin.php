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

class FullAccessCartShareOptionPlugin extends AbstractPlugin implements CartShareOptionPluginInterface
{
    protected const SHARE_OPTION_GROUP_INTERNAL = 'internal';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getShareOptionKey(): string
    {
        return SharedSharedCartConfig::PERMISSION_GROUP_FULL_ACCESS;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
