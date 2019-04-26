<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Communication\Plugin;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\PersistentCartShare\PersistentCartShareConstants;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface;

/**
 * @method \Spryker\Zed\PersistentCartShare\Business\PersistentCartShareBusinessFactory getFactory()
 * @method \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig getConfig()
 * @method \Spryker\Zed\PersistentCartShare\Business\PersistentCartShareFacadeInterface getFacade()
 */
class PersistentCartShareResourceDataExpanderStrategyPlugin extends AbstractPlugin implements ResourceShareResourceDataExpanderStrategyPluginInterface
{
    protected const RESOURCE_TYPE_QUOTE = 'quote';

    /**
     * {@inheritdoc}
     * - Checks if resource type is "quote".
     * - Checks if resource data contains share_option and id_quote parameters.
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

        return true;
    }

    /**
     * {@inheritDoc}
     * - Expands Resource Share Data with IdQuote and ShareOption values.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function expand(ResourceShareTransfer $resourceShareTransfer): ResourceShareResponseTransfer
    {
        $resourceShareData = $resourceShareTransfer->requireResourceShareData()
            ->getResourceShareData()->getData();

        $resourceShareTransfer->getResourceShareData()
            ->setIdQuote($resourceShareData[PersistentCartShareConstants::ID_QUOTE_PARAMETER])
            ->setShareOption($resourceShareData[PersistentCartShareConstants::PARAM_SHARE_OPTION]);

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }
}
