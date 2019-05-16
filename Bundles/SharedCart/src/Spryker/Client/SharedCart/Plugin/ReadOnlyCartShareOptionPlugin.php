<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface;
use Spryker\Shared\SharedCart\SharedCartConfig as SharedSharedCartConfig;

/**
 * @method \Spryker\Client\SharedCart\SharedCartFactory getFactory()
 */
class ReadOnlyCartShareOptionPlugin extends AbstractPlugin implements CartShareOptionPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return SharedSharedCartConfig::PERMISSION_GROUP_READ_ONLY;
    }

    /**
     * {@inheritdoc}
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
        return SharedSharedCartConfig::SHARE_OPTION_GROUP_INTERNAL;
    }
}
