<?php

namespace Spryker\Cart\src\Spryker\Zed\Cart\Business\PluginExecutor;


use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Cart\CartConfig;

class OperationPluginsExecutor implements OperationPluginsExecutorInterface
{
    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface[]
     */
    protected $preCheckPlugins;

    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins;

    /**
     * @var \Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationPostSavePluginInterface[]
     */
    protected $postSavePlugins;

    /**
     * @var \Spryker\Zed\Cart\CartConfig
     */
    protected $cartConfig;

    /**
     * OperationPluginsExecutor constructor.
     *
     * @param \Spryker\Zed\Cart\CartConfig $cartConfig
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\CartPreCheckPluginInterface[] $preCheckPlugins
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\ItemExpanderPluginInterface[] $itemExpanderPlugins
     * @param \Spryker\Zed\CartExtension\Dependency\Plugin\CartOperationPostSavePluginInterface[] $postSavePlugins
     */
    public function __construct(CartConfig $cartConfig, array $preCheckPlugins, array $itemExpanderPlugins, array $postSavePlugins)
    {
        $this->preCheckPlugins = $preCheckPlugins;
        $this->itemExpanderPlugins = $itemExpanderPlugins;
        $this->postSavePlugins = $postSavePlugins;
        $this->cartConfig = $cartConfig;
    }

    /**
     * @inheritDoc
     */
    public function executePreCheckPlugins(CartChangeTransfer $cartChangeTransfer): bool
    {
        // TODO: Implement executePreCheckPlugins() method.
    }

    /**
     * @inheritDoc
     */
    public function executeItemExpanderPlugins(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        // TODO: Implement executeItemExpanderPlugins() method.
    }

    /**
     * @inheritDoc
     */
    public function executePostSavePlugins(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        // TODO: Implement executePostSavePlugins() method.
    }
}
