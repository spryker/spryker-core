<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCode\Operation;

use Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface;
use Spryker\Client\CartCode\Dependency\Client\CartCodeToCartClientInterface;
use Spryker\Client\CartCode\Dependency\Client\CartCodeToQuoteClientInterface;

class CodeRemover
{
    /**
     * @var \Spryker\Client\CartCode\Dependency\Client\CartCodeToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface
     */
    protected $calculationClient;

    /**
     * @var \Spryker\Client\CartCode\Dependency\Client\CartCodeToQuoteClientInterface
     */
    protected $quoteClient;

    /**
     * @var \Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodeHandlerPluginInterface[]
     */
    protected $cartCodeHandlerPlugins;

    /**
     * @param \Spryker\Client\CartCode\Dependency\Client\CartCodeToCartClientInterface $cartClient
     * @param \Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface $cartCodeToCalculation
     * @param \Spryker\Client\CartCode\Dependency\Client\CartCodeToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\CartCodeExtension\Dependency\Plugin\CartCodeHandlerPluginInterface[] $cartCodeHandlerPlugins
     */
    public function __construct(
        CartCodeToCartClientInterface $cartClient,
        CartCodeToCalculationClientInterface $cartCodeToCalculation,
        CartCodeToQuoteClientInterface $quoteClient,
        array $cartCodeHandlerPlugins = []
    ) {
        $this->cartClient = $cartClient;
        $this->calculationClient = $cartCodeToCalculation;
        $this->cartCodeHandlerPlugins = $cartCodeHandlerPlugins;
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param string $code
     *
     * @return void
     */
    public function remove($code)
    {
        $quoteTransfer = $this->cartClient->getQuote();

        foreach ($this->cartCodeHandlerPlugins as $cartCodeHandlerPlugin) {
            $cartCodeHandlerPlugin->removeCode($quoteTransfer, $code);
        }

        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);

//        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();
        $this->quoteClient->setQuote($quoteTransfer);
    }
}
