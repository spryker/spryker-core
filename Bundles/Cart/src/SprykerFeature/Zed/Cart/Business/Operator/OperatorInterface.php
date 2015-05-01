<?php
namespace SprykerFeature\Zed\Cart\Business\Operator;

use SprykerFeature\Shared\Cart\Transfer\CartChangeInterface;
use SprykerFeature\Shared\Cart\Transfer\CartInterface;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;

interface OperatorInterface
{
    /**
     * @param CartChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function executeOperation(CartChangeInterface $cartChange);

    /**
     * @param ItemExpanderPluginInterface $itemExpander
     *
     */
    public function addItemExpanderPlugin(ItemExpanderPluginInterface $itemExpander);
}