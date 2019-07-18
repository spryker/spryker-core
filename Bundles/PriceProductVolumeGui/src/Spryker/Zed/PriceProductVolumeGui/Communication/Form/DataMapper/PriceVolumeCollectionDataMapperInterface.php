<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolumeGui\Communication\Form\DataMapper;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceVolumeCollectionDataMapperInterface
{
    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function mapArrayToPriceProductTransfer(array $data, PriceProductTransfer $priceProductTransfer): PriceProductTransfer;
}
