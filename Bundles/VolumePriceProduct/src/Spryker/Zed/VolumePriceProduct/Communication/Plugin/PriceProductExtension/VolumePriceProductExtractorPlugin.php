<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\VolumePriceProduct\Communication\Plugin\PriceProductExtension;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductMapperPricesExtractorPluginInterface;

/**
 * @method \Spryker\Zed\VolumePriceProduct\Business\VolumePriceProductFacadeInterface getFacade()
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
    public function extractProductPrices(array $priceProductTransfers): array
    {
        return $this->getFacade()->extractVolumePriceProducts($priceProductTransfers);
    }
}
