<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\VolumePriceProduct\Business;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\VolumePriceProduct\Business\VolumePriceProductBusinessFactory getFactory()
 */
class VolumePriceProductFacade extends AbstractFacade implements VolumePriceProductFacadeInterface
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
    public function extractVolumePriceProducts(PriceProductTransfer $priceProductTransfer): array
    {
        return $this->getFactory()->createVolumePriceExtractor()->extractVolumePriceProducts($priceProductTransfer);
    }
}
