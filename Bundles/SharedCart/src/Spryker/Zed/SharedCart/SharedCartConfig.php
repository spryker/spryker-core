<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart;

use Generated\Shared\Transfer\PermissionTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Spryker\Shared\SharedCart\SharedCartConfig as SharedSharedCartConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\SharedCart\Communication\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\WriteSharedCartPermissionPlugin;

class SharedCartConfig extends AbstractBundleConfig
{
    /**
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer[]
     */
    public function getQuotePermissionGroups(): array
    {
        return [
            $this->getReadOnlyPermissionGroup(),
            $this->getFullAccessPermissionGroup(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer
     */
    protected function getReadOnlyPermissionGroup(): QuotePermissionGroupTransfer
    {
        $readOnlyQuotePermissionGroupTransfer = new QuotePermissionGroupTransfer();
        $readOnlyQuotePermissionGroupTransfer
            ->setName(SharedSharedCartConfig::PERMISSION_GROUP_READ_ONLY)
            ->setIsDefault(true)
            ->addPermission((new PermissionTransfer())->setKey(ReadSharedCartPermissionPlugin::KEY));

        return $readOnlyQuotePermissionGroupTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer
     */
    protected function getFullAccessPermissionGroup(): QuotePermissionGroupTransfer
    {
        $fullAccessQuotePermissionGroupTransfer = new QuotePermissionGroupTransfer();
        $fullAccessQuotePermissionGroupTransfer
            ->setName(SharedSharedCartConfig::PERMISSION_GROUP_FULL_ACCESS)
            ->addPermission((new PermissionTransfer())->setKey(ReadSharedCartPermissionPlugin::KEY))
            ->addPermission((new PermissionTransfer())->setKey(WriteSharedCartPermissionPlugin::KEY));

        return $fullAccessQuotePermissionGroupTransfer;
    }
}
