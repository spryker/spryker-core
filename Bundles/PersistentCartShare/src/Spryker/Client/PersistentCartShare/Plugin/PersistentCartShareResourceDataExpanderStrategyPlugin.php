<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\Plugin;

use Generated\Shared\Transfer\ResourceShareTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface;

/**
 * @method \Spryker\Client\PersistentCartShare\PersistentCartShareFactory getFactory()
 */
class PersistentCartShareResourceDataExpanderStrategyPlugin extends AbstractPlugin implements ResourceShareResourceDataExpanderStrategyPluginInterface
{
    protected const SHARE_OPTION_PREVIEW = 'PREVIEW';
    protected const RESOURCE_TYPE_QUOTE = 'quote';
    protected const PARAM_ID_QUOTE = 'id_quote';
    protected const PARAM_SHARE_OPTION = 'share_option';

    /**
     * @inheritDoc
     */
    public function isApplicable(ResourceShareTransfer $resourceShareTransfer): bool
    {
        if ($resourceShareTransfer->getResourceType() !== static::RESOURCE_TYPE_QUOTE) {
            return false;
        }

        $resourceDataArray = $this->getFactory()
            ->getUtilEncodingService()
            ->decodeJson($resourceShareTransfer->getResourceData(), true);

        if (!isset($resourceDataArray[static::PARAM_ID_QUOTE], $resourceDataArray[static::PARAM_SHARE_OPTION])) {
            return false;
        }

        if ($resourceDataArray[static::PARAM_SHARE_OPTION] !== static::SHARE_OPTION_PREVIEW) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc{
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ResourceShareTransfer $resourceShareTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceShareTransfer
     */
    public function expand(ResourceShareTransfer $resourceShareTransfer): ResourceShareTransfer
    {
        return $resourceShareTransfer->setPersistentCartShareResourceData(
            $this->getFactory()
                ->createResourceDataReader()
                ->getResourceDataFromResourceShareTransfer($resourceShareTransfer)
        );
    }
}
