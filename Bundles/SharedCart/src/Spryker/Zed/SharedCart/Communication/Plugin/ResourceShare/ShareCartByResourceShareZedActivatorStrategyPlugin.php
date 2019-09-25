<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin\ResourceShare;

use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareZedActivatorStrategyPluginInterface;

/**
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 */
class ShareCartByResourceShareZedActivatorStrategyPlugin extends AbstractPlugin implements ResourceShareZedActivatorStrategyPluginInterface
{
    /**
     * @uses \Spryker\Shared\PersistentCartShare\PersistentCartShareConfig::RESOURCE_TYPE_QUOTE
     */
    protected const RESOURCE_TYPE_QUOTE = 'quote';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_READ_ONLY
     */
    protected const PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS
     */
    protected const PERMISSION_GROUP_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * {@inheritDoc}
     * - Creates cart share for provided Quote and provided company user within the same business unit.
     * - Updates permission to Full-access, if resource was shared with higher permission.
     * - Returns 'isSuccessful=true' with ResourceShareTransfer if cart was shared successfully.
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
        return $this->getFacade()->shareCartByResourceShareRequest($resourceShareRequestTransfer);
    }

    /**
     * {@inheritDoc}
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
        $customerTransfer = $resourceShareRequestTransfer->getCustomer();
        if (!$customerTransfer || !$customerTransfer->getCompanyUserTransfer()) {
            return false;
        }

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
