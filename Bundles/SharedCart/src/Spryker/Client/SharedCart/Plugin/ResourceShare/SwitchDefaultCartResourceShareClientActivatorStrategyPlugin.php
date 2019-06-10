<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Plugin\ResourceShare;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareClientActivatorStrategyPluginInterface;

/**
 * @method \Spryker\Client\SharedCart\SharedCartClientInterface getClient()
 */
class SwitchDefaultCartResourceShareClientActivatorStrategyPlugin extends AbstractPlugin implements ResourceShareClientActivatorStrategyPluginInterface
{
    /**
     * @uses \Spryker\Shared\PersistentCartShare\PersistentCartShareConfig::RESOURCE_TYPE_QUOTE
     */
    protected const RESOURCE_TYPE_QUOTE = 'quote';
    protected const PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';
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
        return $this->getClient()->switchDefaultCartByResourceShare($resourceShareRequestTransfer);
    }

    /**
     * {@inheritdoc}
     * - Returns 'true', when resource type is Quote, share option is Read-only or Full access and provided customer is company user.
     * - Returns 'false' otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareRequestTransfer $resourceShareRequestTransfer): bool
    {
        $resourceShareTransfer = $resourceShareRequestTransfer->getResourceShare();
        $resourceShareTransfer->requireResourceType();
        if ($resourceShareTransfer->getResourceType() !== static::RESOURCE_TYPE_QUOTE) {
            return false;
        }

        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        return in_array($resourceShareDataTransfer->getShareOption(), [static::PERMISSION_GROUP_READ_ONLY, static::PERMISSION_GROUP_FULL_ACCESS], true);
    }
}
