<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspAssetManagement\Widget;

use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

class SspAssetListWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer|null $sspAssetCollectionTransfer
     */
    public function __construct(?SspAssetCollectionTransfer $sspAssetCollectionTransfer)
    {
        $this->addParameter('totalItems', $sspAssetCollectionTransfer?->getPagination()?->getNbResults());
        $this->addParameter('sspAssets', $sspAssetCollectionTransfer?->getSspAssets());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SspAssetListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SspAssetManagement/views/ssp-asset-list/ssp-asset-list.twig';
    }
}
