<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCode\Operation;

use Spryker\Client\CartCode\Dependency\Client\CartCodeToCalculationClientInterface;
use Spryker\Client\CartCode\Dependency\Client\CartCodeToCartClientInterface;
use Spryker\Client\CartCode\Dependency\Client\CartCodeToQuoteClientInterface;

class CodeAdder
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
    public function add($code)
    {
        $quoteTransfer = $this->cartClient->getQuote();

        foreach ($this->cartCodeHandlerPlugins as $cartCodeHandlerPlugin) {
            $cartCodeHandlerPlugin->addCandidate($quoteTransfer, $code);
        }

        $quoteTransfer = $this->calculationClient->recalculate($quoteTransfer);
        $this->quoteClient->setQuote($quoteTransfer);

        // TODO check if we can return messages instead of setting them to flash messages
//        foreach ($this->cartCodeHandlerPlugins as $cartCodeHandlerPlugin) {
//            $codeCalculationResult = $cartCodeHandlerPlugin->getCodeRecalculationResult($quoteTransfer, $code);
//
//            if ($codeCalculationResult->getIsSuccess()) {
//                $this->messengerClient->addSuccessMessage($cartCodeHandlerPlugin->getSuccessMessage($quoteTransfer, $code));
//                return;
//            }
//
//            if ($this->hasErrors($codeCalculationResult)) {
//                $this->addErrors($codeCalculationResult);
//                return;
//            }
//        }

//        $this->handleCodeApplicationFailure();
//        $this->zedRequestClient->addFlashMessagesFromLastZedRequest();
    }
}
