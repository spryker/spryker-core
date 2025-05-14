<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspAssetManagement\Plugin\CartPage;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CartPageExtension\Dependency\Plugin\PreAddToCartPluginInterface;

/**
 * @method \SprykerFeature\Yves\SspAssetManagement\SspAssetManagementFactory getFactory()
 * @method \SprykerFeature\Yves\SspAssetManagement\SspAssetManagementConfig getConfig()
 */
class SspAssetPreAddToCartPlugin extends AbstractPlugin implements PreAddToCartPluginInterface
{
    /**
     * @var string
     */
    protected const PARAM_ASSET_REFERENCE = 'assetReference';

    /**
     * {@inheritDoc}
     * - Maps asset reference from request parameters to ItemTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function preAddToCart(ItemTransfer $itemTransfer, array $params): ItemTransfer
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
