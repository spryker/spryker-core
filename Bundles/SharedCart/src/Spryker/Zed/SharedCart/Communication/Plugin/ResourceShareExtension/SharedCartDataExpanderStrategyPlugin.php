<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin\ResourceShareExtension;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Shared\SharedCart\SharedCartConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface;

/**
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 */
class SharedCartDataExpanderStrategyPlugin extends AbstractPlugin implements ResourceShareResourceDataExpanderStrategyPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands ResourceShareTransfer::ResourceShareDataTransfer with shareable cart details.
     * - Returns ResourceShareResponseTransfer with 'isSuccessful=true' on success.
     * - Returns ResourceShareResponseTransfer with 'isSuccessful=false' and error messages otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function expand(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        return $this->getFacade()->applyResourceShareDataExpanderStrategy($resourceShareTransfer);
    }

    /**
     * {@inheritdoc}
     * - Returns 'true', when resource type is Quote, and share option is Read-only or Full access.
     * - Returns 'false' otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareTransfer $resourceShareTransfer): bool
    {
        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();

        $shareOption = $resourceShareDataTransfer->getData()[SharedCartConfig::KEY_SHARE_OPTION] ?? null;
        if (!$shareOption) {
            return false;
        }

        return in_array($shareOption, [SharedCartConfig::PERMISSION_GROUP_READ_ONLY, SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS], true);
    }
}
