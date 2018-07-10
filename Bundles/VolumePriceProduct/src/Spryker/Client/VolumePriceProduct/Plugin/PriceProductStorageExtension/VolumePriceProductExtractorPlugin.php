<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\VolumePriceProduct\Plugin\PriceProductStorageExtension;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PriceProductStorageExtension\Dependency\Plugin\PriceProductMapperPricesExtractorPluginInterface;

/**
 * @method \Spryker\Client\VolumePriceProduct\VolumePriceProductFactory getFactory()
 */
class VolumePriceProductExtractorPlugin extends AbstractPlugin implements PriceProductMapperPricesExtractorPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductPrices(PriceProductTransfer $priceProductTransfer): array
    {
        return $this->getFactory()->createVolumePriceExtractor()->extractVolumePriceProducts($priceProductTransfer);
    }
}
