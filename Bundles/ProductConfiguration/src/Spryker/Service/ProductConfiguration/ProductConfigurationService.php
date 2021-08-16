<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfiguration;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\ProductConfiguration\ProductConfigurationServiceFactory getFactory()
 */
class ProductConfigurationService extends AbstractService implements ProductConfigurationServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return string
     */
    public function getProductConfigurationInstanceHash(ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer): string
    {
        return $this->getFactory()
            ->createProductConfigurationInstanceHashGenerator()
            ->getProductConfigurationInstanceHash($productConfigurationInstanceTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function filterProductConfigurationPrices(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array {
        return $this->getFactory()
            ->createPriceProductConfigurationFilter()
            ->filterProductConfigurationPrices($priceProductTransfers, $priceProductFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function filterProductConfigurationVolumePrices(
        array $priceProductTransfers,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): array {
        return $this->getFactory()
            ->createVolumePriceProductConfigurationFilter()
            ->filterProductConfigurationVolumePrices($priceProductTransfers, $priceProductFilterTransfer);
    }
}
