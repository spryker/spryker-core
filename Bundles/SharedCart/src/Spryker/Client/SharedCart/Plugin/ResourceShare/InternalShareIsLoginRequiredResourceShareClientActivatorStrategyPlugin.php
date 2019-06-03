<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Plugin\ResourceShare;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareClientActivatorStrategyPluginInterface;

/**
 * @method \Spryker\Client\SharedCart\SharedCartClient getClient()
 * @method \Spryker\Client\SharedCart\SharedCartFactory getFactory()
 */
class InternalShareIsLoginRequiredResourceShareClientActivatorStrategyPlugin extends AbstractPlugin implements ResourceShareClientActivatorStrategyPluginInterface
{
    protected const RESOURCE_TYPE_QUOTE = 'quote';
    protected const PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';
    protected const PERMISSION_GROUP_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return bool
     */
    public function isLoginRequired(?CustomerTransfer $customerTransfer): bool
    {
        return $customerTransfer === null;
    }

    /**
     * {@inheritdoc}
     * - Returns 'true', when resource type is Quote.
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
        if ($resourceShareTransfer->getResourceType() !== static::RESOURCE_TYPE_QUOTE) {
            return false;
        }

        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        return in_array($resourceShareDataTransfer->getShareOption(), [static::PERMISSION_GROUP_READ_ONLY, static::PERMISSION_GROUP_FULL_ACCESS], true);
    }

    /**
     * {@inheritdoc}
     * - Does nothing, as no additional actions required for cart preview.
     * - Returns ResourceShareResponseTransfer with isSuccessful=true and provided ResourceShare.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function execute(ResourceShareRequestTransfer $resourceShareRequestTransfer): ResourceShareResponseTransfer
    {
        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareRequestTransfer->getResourceShare());
    }
}
