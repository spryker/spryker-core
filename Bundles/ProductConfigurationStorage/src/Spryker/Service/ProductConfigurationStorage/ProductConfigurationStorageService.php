<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfigurationStorage;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\ProductConfigurationStorage\ProductConfigurationStorageServiceFactory getFactory()
 */
class ProductConfigurationStorageService extends AbstractService implements ProductConfigurationStorageServiceInterface
{
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
