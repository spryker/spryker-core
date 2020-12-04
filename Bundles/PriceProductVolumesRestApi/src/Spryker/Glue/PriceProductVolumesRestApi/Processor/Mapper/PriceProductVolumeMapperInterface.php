<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PriceProductVolumesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\RestProductPriceAttributesTransfer;

interface PriceProductVolumeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     * @param \Generated\Shared\Transfer\RestProductPriceAttributesTransfer $restProductPriceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductPriceAttributesTransfer
     */
    public function mapPriceProductVolumeDataToRestProductPricesAttributes(
        CurrentProductPriceTransfer $currentProductPriceTransfer,
        RestProductPriceAttributesTransfer $restProductPriceAttributesTransfer
    ): RestProductPriceAttributesTransfer;
}
