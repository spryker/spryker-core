<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Price\Business\PriceBusinessFactory getFactory()
 */
class PriceFacade extends AbstractFacade implements PriceFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getPriceModes()
    {
        return $this->getFactory()
            ->getConfig()
            ->createSharedConfig()
            ->getPriceModes();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getDefaultPriceMode()
    {
        return $this->getFactory()
            ->getConfig()
            ->createSharedConfig()
            ->getDefaultPriceMode();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getNetPriceModeIdentifier()
    {
        return $this->getFactory()
            ->getConfig()
            ->createSharedConfig()
            ->getNetPriceModeIdentifier();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getGrossPriceModeIdentifier()
    {
        return $this->getFactory()
           ->getConfig()
           ->createSharedConfig()
           ->getGrossPriceModeIdentifier();
    }
}
