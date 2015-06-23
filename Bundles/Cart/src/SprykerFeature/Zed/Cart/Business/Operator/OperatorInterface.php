<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Operator;

use Generated\Shared\Transfer\CartCartChangeInterfaceTransfer;
use Generated\Shared\Transfer\CartCartInterfaceTransfer;
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
