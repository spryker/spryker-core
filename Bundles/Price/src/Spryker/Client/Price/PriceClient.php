<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Price\PriceFactory getFactory()
 * @method \Spryker\Client\Price\PriceConfig getConfig()
 */
class PriceClient extends AbstractClient implements PriceClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getCurrentPriceMode()
    {
        return $this->getFactory()
            ->createPriceModeResolver()
            ->getCurrentPriceMode();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $priceMode
     *
     * @return void
     */
    public function switchPriceMode(string $priceMode): void
    {
        $this->getFactory()
            ->createPriceModeSwitcher()
            ->switchPriceMode($priceMode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getGrossPriceModeIdentifier()
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getGrossPriceModeIdentifier();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getNetPriceModeIdentifier()
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getNetPriceModeIdentifier();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getPriceModes()
    {
        return $this->getFactory()
            ->getModuleConfig()
            ->getPriceModes();
    }
}
