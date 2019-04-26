<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Communication\Plugin;

use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Shared\PersistentCartShare\PersistentCartShareConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface;

/**
 * @method \Spryker\Zed\PersistentCartShare\Business\PersistentCartShareFacade getFacade()
 * @method \Spryker\Zed\PersistentCartShare\Communication\PersistentCartShareCommunicationFactory getFactory()
 * @method \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig getConfig()
 */
class CartPreviewActivatorStrategyPlugin extends AbstractPlugin implements ResourceShareActivatorStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function isLoginRequired(): bool
    {
        return false;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return bool
     */
    public function isApplicable(ResourceShareTransfer $resourceShareTransfer): bool
    {
        if ($resourceShareTransfer->getResourceType() !== PersistentCartShareConstants::RESOURCE_TYPE_QUOTE) {
            return false;
        }

        $resourceShareRawData = $resourceShareTransfer
            ->requireResourceShareData()->getResourceShareData()
            ->requireData()->getData();

        if (!isset($resourceShareRawData[PersistentCartShareConstants::ID_QUOTE_PARAMETER], $resourceShareRawData[PersistentCartShareConstants::PARAM_SHARE_OPTION])) {
            return false;
        }

        if ($resourceShareRawData[PersistentCartShareConstants::PARAM_SHARE_OPTION] !== PersistentCartShareConstants::SHARE_OPTION_PREVIEW) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function execute(ResourceShareTransfer $resourceShareTransfer): void
    {
        //do nothing
    }
}
