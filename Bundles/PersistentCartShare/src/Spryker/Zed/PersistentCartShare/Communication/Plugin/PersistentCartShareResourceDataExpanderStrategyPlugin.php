<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartShare\Communication\Plugin;

use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface;

/**
 * @method \Spryker\Zed\PersistentCartShare\Business\PersistentCartShareBusinessFactory getFactory()
 * @method \Spryker\Zed\PersistentCartShare\PersistentCartShareConfig getConfig()
 * @method \Spryker\Zed\PersistentCartShare\Business\PersistentCartShareFacadeInterface getFacade()
 */
class PersistentCartShareResourceDataExpanderStrategyPlugin extends AbstractPlugin implements ResourceShareResourceDataExpanderStrategyPluginInterface
{
    protected const SHARE_OPTION_PREVIEW = 'PREVIEW';
    protected const RESOURCE_TYPE_QUOTE = 'quote';
    protected const PARAM_ID_QUOTE = 'id_quote';
    protected const PARAM_SHARE_OPTION = 'share_option';

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
        if ($resourceShareTransfer->getResourceType() !== static::RESOURCE_TYPE_QUOTE) {
            return false;
        }

        $resourceShareRawData = $resourceShareTransfer
            ->requireResourceShareData()->getResourceShareData()
            ->requireData()->getData();

        if (!isset($resourceShareRawData[static::PARAM_ID_QUOTE], $resourceShareRawData[static::PARAM_SHARE_OPTION])) {
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
            ->setIdQuote($resourceShareData[static::PARAM_ID_QUOTE])
            ->setShareOption($resourceShareData[static::PARAM_SHARE_OPTION]);

        return (new ResourceShareResponseTransfer())
            ->setIsSuccessful(true)
            ->setResourceShare($resourceShareTransfer);
    }
}
