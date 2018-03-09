<?php


namespace Spryker\Zed\CartExtension\Dependency\Plugin;


use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CartTerminationPluginInterface
{
    public function isTerminated(string $terminationEventName, CartChangeTransfer $cartChangeTransfer, QuoteTransfer $calculatedQuoteTransfer): bool;
}