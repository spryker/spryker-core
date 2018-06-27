<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PriceProduct\PriceProductFactory getFactory()
 * @method \Spryker\Client\PriceProduct\PriceProductConfig getConfig()
 */
class PriceProductClient extends AbstractClient implements PriceProductClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getPriceTypeDefaultName()
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getPriceTypeDefaultName();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $priceMap
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductPrice(array $priceMap)
    {
        return $this->getFactory()
            ->createProductPriceResolver()
            ->resolve($priceMap);
    }

    /**
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function resolveProductPriceTransfer(array $priceProductTransfers)
    {
        return $this->getFactory()
            ->createProductPriceResolver()
            ->resolveTransfer($priceProductTransfers);
    }
}
