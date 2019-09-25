<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Communication\Plugin\PriceProductExtension;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductReaderPricesExtractorPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductVolume\Business\PriceProductVolumeFacadeInterface getFacade()
 */
class PriceProductVolumeExtractorPlugin extends AbstractPlugin implements PriceProductReaderPricesExtractorPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductPricesForProductAbstract(array $priceProductTransfers): array
    {
        return $this->getFacade()->extractPriceProductVolumesForProductAbstract($priceProductTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function extractProductPricesForProductConcrete(array $priceProductTransfers): array
    {
        return $this->getFacade()->extractPriceProductVolumesForProductConcrete($priceProductTransfers);
    }
}
