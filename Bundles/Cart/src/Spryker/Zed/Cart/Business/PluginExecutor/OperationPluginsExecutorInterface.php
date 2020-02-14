<?php


namespace Spryker\Cart\src\Spryker\Zed\Cart\Business\PluginExecutor;


use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface OperationPluginsExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return bool
     */
    public function executePreCheckPlugins(CartChangeTransfer $cartChangeTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function executeItemExpanderPlugins(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function executePostSavePlugins(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
