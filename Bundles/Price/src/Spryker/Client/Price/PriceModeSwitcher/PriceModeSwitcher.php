<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price\PriceModeSwitcher;

use Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface;
use Spryker\Client\Price\Exception\UnknownPriceModeException;
use Spryker\Client\Price\PriceConfig;

class PriceModeSwitcher implements PriceModeSwitcherInterface
{
    /**
     * @var \Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\Price\PriceConfig
     */
    protected $priceConfig;

    /**
     * @var \Spryker\Client\PriceExtension\Dependency\Plugin\PriceModePostUpdatePluginInterface[]
     */
    protected $priceModePostUpdatePlugins;

    /**
     * @param \Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\Price\PriceConfig $priceConfig
     * @param \Spryker\Client\PriceExtension\Dependency\Plugin\PriceModePostUpdatePluginInterface[] $priceModePostUpdatePlugins
     */
    public function __construct(PriceToQuoteClientInterface $quoteClient, PriceConfig $priceConfig, array $priceModePostUpdatePlugins)
    {
        $this->quoteClient = $quoteClient;
        $this->priceConfig = $priceConfig;
        $this->priceModePostUpdatePlugins = $priceModePostUpdatePlugins;
    }

    /**
     * @param string $priceMode
     *
     * @throws \Spryker\Client\Price\Exception\UnknownPriceModeException
     *
     * @return void
     */
    public function switchPriceMode(string $priceMode): void
    {
        $priceModes = $this->priceConfig->getPriceModes();

        if (!isset($priceModes[$priceMode])) {
            throw new UnknownPriceModeException(
                sprintf('Unknown price mode "%s".' . $priceMode)
            );
        }
        $quoteTransfer = $this->quoteClient->getQuote();
        $quoteTransfer->setPriceMode($priceMode);
        $this->quoteClient->setQuote($quoteTransfer);
        $this->executePriceModePostUpdatePlugins($priceMode);
    }

    /**
     * @param string $priceMode
     *
     * @return void
     */
    protected function executePriceModePostUpdatePlugins(string $priceMode): void
    {
        foreach ($this->priceModePostUpdatePlugins as $priceModePostUpdatePlugin) {
            $priceModePostUpdatePlugin->execute($priceMode);
        }
    }
}
