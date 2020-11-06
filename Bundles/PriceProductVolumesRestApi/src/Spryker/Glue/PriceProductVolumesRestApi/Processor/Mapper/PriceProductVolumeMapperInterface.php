<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PriceProductVolumesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestProductPricesAttributesTransfer;

interface PriceProductVolumeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\RestProductPricesAttributesTransfer $restProductPriceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPricesAttributesTransfer
     */
    public function mapPriceProductVolumeDataToRestProductPricesAttributes(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        RestProductPricesAttributesTransfer $restProductPriceAttributesTransfer
    ): RestProductPricesAttributesTransfer;
}
