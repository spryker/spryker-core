<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Plugin\ResourceShare;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface;

/**
 * @method \Spryker\Client\MultiCart\MultiCartClientInterface getClient()
 */
class SwitchDefaultCartResourceShareActivatorStrategyPlugin extends AbstractPlugin implements ResourceShareActivatorStrategyPluginInterface
{
    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::QUOTE_RESOURCE_TYPE
     */
    protected const QUOTE_RESOURCE_TYPE = 'quote';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_READ_ONLY
     */
    protected const PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS
     */
    protected const PERMISSION_GROUP_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * {@inheritdoc}
     * - Switches default cart for provided Quote and company user.
     * - Returns 'isSuccessful=true' with ResourceShareTransfer if cart was switched successfully.
     * - Returns 'isSuccessful=false' with error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function execute(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return $this->getClient()->applySwitchDefaultCartResourceShareActivatorStrategy($resourceShareRequestTransfer);
    }

    /**
     * {@inheritdoc}
     * - Returns 'true', as activator strategy expects the customer to be logged in.
     *
     * @api
     *
     * @return bool
     */
    public function isLoginRequired(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     * - Checks if strategy plugin is applicable, based on resource data and provided customer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareRequestTransfer $resourceShareRequestTransfer): bool
    {
        $customerTransfer = $resourceShareRequestTransfer->getCustomer();
        if (!$customerTransfer->getCompanyUserTransfer()) {
            return false;
        }

        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();
        $resourceShareTransfer->requireResourceType();
        if ($resourceShareTransfer->getResourceType() !== static::QUOTE_RESOURCE_TYPE) {
            return false;
        }

        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        return in_array($resourceShareDataTransfer->getShareOption(), [static::PERMISSION_GROUP_READ_ONLY, static::PERMISSION_GROUP_FULL_ACCESS], true);
    }
}
