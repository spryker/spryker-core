<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspAssetInfoForItemWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_ASSET = 'asset';

    /**
     * @var string
     */
    protected const PARAMETER_IS_COMPATIBILITY_VISIBLE = 'isCompatibilityVisible';

    /**
     * @var string
     */
    protected const PARAMETER_ID_PRODUCT = 'idProduct';

    public function __construct(ItemTransfer $itemTransfer)
    {
        $this->addAssetParameter($itemTransfer);
        $this->addIdProductParameter($itemTransfer);
        $this->addIsCompatibilityVisibleParameter($itemTransfer);
    }

    public static function getName(): string
    {
        return 'SspAssetInfoForItemWidget';
    }

    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/asset-info-for-item/asset-info-for-item.twig';
    }

    protected function addAssetParameter(ItemTransfer $itemTransfer): void
    {
        $this->addParameter(static::PARAMETER_ASSET, $itemTransfer->getSspAsset());
    }

    protected function addIdProductParameter(ItemTransfer $itemTransfer): void
    {
        $this->addParameter(static::PARAMETER_ID_PRODUCT, $itemTransfer->getId());
    }

    protected function addIsCompatibilityVisibleParameter(ItemTransfer $itemTransfer): void
    {
        $this->addParameter(static::PARAMETER_IS_COMPATIBILITY_VISIBLE, !$itemTransfer->getOrderItemReference());
    }
}
