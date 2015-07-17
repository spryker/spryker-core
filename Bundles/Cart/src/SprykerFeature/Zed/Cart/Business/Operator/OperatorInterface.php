<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Operator;

use Generated\Shared\Cart\CartInterface;
use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;

interface OperatorInterface
{

    /**
     * @param ChangeInterface $cartChange
     *
     * @return CartInterface
     */
    public function executeOperation(ChangeInterface $cartChange);

    /**
     * @param ItemExpanderPluginInterface $itemExpander
     */
    public function addItemExpanderPlugin(ItemExpanderPluginInterface $itemExpander);

}
