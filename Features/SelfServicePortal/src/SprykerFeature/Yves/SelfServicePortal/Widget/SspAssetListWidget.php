<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class SspAssetListWidget extends AbstractWidget
{
    public function __construct(?SspAssetCollectionTransfer $sspAssetCollectionTransfer)
    {
        $this->addParameter('totalItems', $sspAssetCollectionTransfer?->getPagination()?->getNbResults());
        $this->addParameter('sspAssets', $sspAssetCollectionTransfer?->getSspAssets());
    }

    public static function getName(): string
    {
        return 'SspAssetListWidget';
    }

    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/asset-list/asset-list.twig';
    }
}
