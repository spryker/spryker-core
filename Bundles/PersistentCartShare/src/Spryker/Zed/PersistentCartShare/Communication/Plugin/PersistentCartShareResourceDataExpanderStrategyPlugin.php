<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Communication\Plugin;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Zed\PersistentCartShare\PersistentCartShareConfig;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface;

/**
 * @method \Spryker\Zed\PersistentCartShare\Business\PersistentCartShareFacadeInterface getFacade()
 * @method \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig getConfig()
 */
class PersistentCartShareResourceDataExpanderStrategyPlugin extends AbstractPlugin implements ResourceShareResourceDataExpanderStrategyPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands ResourceShareDataTransfer with 'idQuote' and 'shareOption' values.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function expand(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareTransfer->requireResourceShareData();
        $resourceShareDataTransfer = $resourceShareTransfer->getResourceShareData();
        $resourceShareData = $resourceShareDataTransfer->getData();

        $resourceShareDataTransfer->setIdQuote($resourceShareData[PersistentCartShareConfig::KEY_ID_QUOTE])
            ->setShareOption($resourceShareData[PersistentCartShareConfig::KEY_SHARE_OPTION]);

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }

    /**
     * {@inheritdoc}
     * - Returns true if resource type is 'quote' and resource share data contains 'id_quote' value, and 'share_option=preview'.
     * - Returns false otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareTransfer $resourceShareTransfer): bool
    {
        if ($resourceShareTransfer->getResourceType() !== PersistentCartShareConfig::RESOURCE_TYPE_QUOTE) {
            return false;
        }

        $resourceShareTransfer->requireResourceShareData();
        $resourceShareData = $resourceShareTransfer->getResourceShareData()
            ->getData();

        if (!isset($resourceShareData[PersistentCartShareConfig::KEY_ID_QUOTE], $resourceShareData[PersistentCartShareConfig::KEY_SHARE_OPTION])
            || $resourceShareData[PersistentCartShareConfig::KEY_SHARE_OPTION] !== PersistentCartShareConfig::SHARE_OPTION_PREVIEW
        ) {
            return false;
        }

        return true;
    }
}
