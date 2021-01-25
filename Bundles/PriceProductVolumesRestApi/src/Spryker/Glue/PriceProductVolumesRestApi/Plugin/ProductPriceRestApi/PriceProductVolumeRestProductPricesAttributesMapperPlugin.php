<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PriceProductVolumesRestApi\Plugin\ProductPriceRestApi;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestProductPriceAttributesTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductPricesRestApiExtension\Dependency\Plugin\RestProductPricesAttributesMapperPluginInterface;

/**
 * @method \Spryker\Glue\PriceProductVolumesRestApi\PriceProductVolumesRestApiFactory getFactory()
 */
class PriceProductVolumeRestProductPricesAttributesMapperPlugin extends AbstractPlugin implements RestProductPricesAttributesMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps price product volume data to RestProductPricesAttributesTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\RestProductPriceAttributesTransfer $restProductPriceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPriceAttributesTransfer
     */
    public function map(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        RestProductPriceAttributesTransfer $restProductPriceAttributesTransfer
    ): RestProductPriceAttributesTransfer {
        return $this->getFactory()
            ->createPriceProductVolumeMapper()
            ->mapPriceProductVolumeDataToRestProductPricesAttributes(
                $currentProductPriceTransfer,
                $restProductPriceAttributesTransfer
            );
    }
}
