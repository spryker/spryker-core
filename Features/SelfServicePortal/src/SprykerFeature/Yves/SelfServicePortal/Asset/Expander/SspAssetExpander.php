<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;

class SspAssetExpander implements SspAssetExpanderInterface
{
    /**
     * @var string
     */
    protected const PARAM_ASSET_REFERENCE = 'assetReference';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithSspAsset(ItemTransfer $itemTransfer, array $params): ItemTransfer
    {
        $assetReference = $params[static::PARAM_ASSET_REFERENCE] ?? null;

        if ($assetReference) {
            $itemTransfer->setSspAsset(
                (new SspAssetTransfer())->setReference($assetReference),
            );
        }

        return $itemTransfer;
    }
}
