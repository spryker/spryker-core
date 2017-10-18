<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Price\PriceModeSwitcher;

use Spryker\Yves\Price\Dependency\Client\PriceToQuoteClientInterface;
use Spryker\Yves\Price\Exception\UnknownPriceModeException;
use Spryker\Yves\Price\PriceConfig;

class PriceModeSwitcher implements PriceModeSwitcherInterface
{

    /**
     * @var \Spryker\Yves\Price\Dependency\Client\PriceToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Yves\Price\PriceConfig
     */
    protected $priceConfig;

    /**
     * @param \Spryker\Yves\Price\Dependency\Client\PriceToQuoteClientInterface $quoteClient
     * @param \Spryker\Yves\Price\PriceConfig $priceConfig
     */
    public function __construct(PriceToQuoteClientInterface $quoteClient, PriceConfig $priceConfig)
    {
        $this->quoteClient = $quoteClient;
        $this->priceConfig = $priceConfig;
    }

    /**
     * @param string $priceMode
     *
     * @throws \Spryker\Yves\Price\Exception\UnknownPriceModeException
     *
     * @return void
     */
    public function switchPriceMode($priceMode)
    {
        $priceModes = $this->priceConfig->createSharedConfig()->getPriceModes();
        if (!isset($priceModes[$priceMode])) {
            throw new UnknownPriceModeException(
                sprintf('Unknown price mode "%s".'. $priceMode)
            );
        }

        $quoteTransfer = $this->quoteClient->getQuote();
        $quoteTransfer->setPriceMode($priceMode);
        $this->quoteClient->setQuote($quoteTransfer);
    }

}
