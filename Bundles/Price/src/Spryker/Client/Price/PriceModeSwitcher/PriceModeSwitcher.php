<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price\PriceModeSwitcher;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface;
use Spryker\Client\Price\Exception\UnknownPriceModeException;
use Spryker\Client\Price\PriceConfig;
use Spryker\Client\Price\PriceModeCache\PriceModeCacheInterface;

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
     * @var list<\Spryker\Client\PriceExtension\Dependency\Plugin\PriceModePostUpdatePluginInterface>
     */
    protected $priceModePostUpdatePlugins;

    /**
     * @var list<\Spryker\Client\PriceExtension\Dependency\Plugin\CurrentPriceModePreCheckPluginInterface>
     */
    protected array $currentPriceModePreCheckPlugins;

    /**
     * @var \Spryker\Client\Price\PriceModeCache\PriceModeCacheInterface
     */
    protected $priceModeCache;

    /**
     * @param \Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\Price\PriceConfig $priceConfig
     * @param list<\Spryker\Client\PriceExtension\Dependency\Plugin\PriceModePostUpdatePluginInterface> $priceModePostUpdatePlugins
     * @param list<\Spryker\Client\PriceExtension\Dependency\Plugin\CurrentPriceModePreCheckPluginInterface> $currentPriceModePreCheckPlugins
     * @param \Spryker\Client\Price\PriceModeCache\PriceModeCacheInterface $priceModeCache
     */
    public function __construct(
        PriceToQuoteClientInterface $quoteClient,
        PriceConfig $priceConfig,
        array $priceModePostUpdatePlugins,
        array $currentPriceModePreCheckPlugins,
        PriceModeCacheInterface $priceModeCache
    ) {
        $this->quoteClient = $quoteClient;
        $this->priceConfig = $priceConfig;
        $this->priceModePostUpdatePlugins = $priceModePostUpdatePlugins;
        $this->currentPriceModePreCheckPlugins = $currentPriceModePreCheckPlugins;
        $this->priceModeCache = $priceModeCache;
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
                sprintf('Unknown price mode "%s".', $priceMode),
            );
        }
        $quoteTransfer = $this->quoteClient->getQuote();

        if (!$this->executeCurrentPriceModePreCheckPlugins($priceMode, $quoteTransfer)) {
            return;
        }

        $quoteTransfer->setPriceMode($priceMode);
        $this->quoteClient->setQuote($quoteTransfer);
        $this->executePriceModePostUpdatePlugins($priceMode);

        $this->priceModeCache->cache($priceMode);
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

    /**
     * @param string $priceMode
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function executeCurrentPriceModePreCheckPlugins(string $priceMode, QuoteTransfer $quoteTransfer): bool
    {
        foreach ($this->currentPriceModePreCheckPlugins as $currentPriceModePreCheckPlugin) {
            if (!$currentPriceModePreCheckPlugin->isPriceModeChangeAllowed($priceMode, $quoteTransfer)) {
                return false;
            }
        }

        return true;
    }
}
