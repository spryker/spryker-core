<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductVolume\Plugin\PriceProductStorageExtension;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductMapperPricesExtractorPluginInterface;

/**
 * @method \Spryker\Client\PriceProductVolume\PriceProductVolumeFactory getFactory()
 */
class PriceProductVolumeExtractorPlugin extends AbstractPlugin implements PriceProductMapperPricesExtractorPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductPrices(PriceProductTransfer $priceProductTransfer): array
    {
        return $this->getFactory()
            ->createVolumePriceExtractor()
            ->extractPriceProductVolumes($priceProductTransfer);
    }
}
