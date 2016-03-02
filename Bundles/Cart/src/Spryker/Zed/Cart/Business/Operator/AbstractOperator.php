<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Operator;

use Generated\Shared\Transfer\CartTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Generated\Shared\Transfer\GroupableContainerTransfer;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Cart\Business\Model\CalculableContainer;
use Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToItemGrouperInterface;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade;

abstract class AbstractOperator implements OperatorInterface
{

    /**
     * @var \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface
     */
    protected $storageProvider;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $messenger;

    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface
     */
    protected $cartCalculator;

    /**
     * @var \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins = [];

    /**
     * @var \Spryker\Zed\Cart\Dependency\Facade\CartToItemGrouperInterface
     */
    protected $itemGrouperFacade;

    /**
     * @param \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface $storageProvider
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface $cartCalculator
     * @param \Spryker\Zed\Cart\Dependency\Facade\CartToItemGrouperInterface $itemGrouperFacade
     * @param \Psr\Log\LoggerInterface $messenger
     */
    public function __construct(
        StorageProviderInterface $storageProvider,
        CartToCalculationInterface $cartCalculator,
        CartToItemGrouperInterface $itemGrouperFacade,
        LoggerInterface $messenger = null //@todo to be discussed
    ) {

        $this->storageProvider = $storageProvider;
        $this->messenger = $messenger;
        $this->cartCalculator = $cartCalculator;
        $this->itemGrouperFacade = $itemGrouperFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $cartChange
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    public function executeOperation(ChangeTransfer $cartChange)
    {
        $changedItems = $this->expandChangedItems($cartChange);
        $cart = $this->changeCart($cartChange->getCart(), $changedItems);

        if ($this->messenger) {
            $this->messenger->info($this->createSuccessMessage());
        }

        $cart = $this->recalculate($cart);

        return $cart;
    }

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    private function recalculate(CartTransfer $cart)
    {
        $calculableCart = new CalculableContainer($cart);
        $cart = $this->cartCalculator->recalculate($calculableCart);

        return $cart->getCalculableObject();
    }

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    abstract protected function changeCart(CartTransfer $cart, ChangeTransfer $change);

    /**
     * @return string
     */
    abstract protected function createSuccessMessage();

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    protected function expandChangedItems(ChangeTransfer $change)
    {
        foreach ($this->itemExpanderPlugins as $itemExpander) {
            $change = $itemExpander->expandItems($change);
        }

        return $change;
    }

    /**
     * @param \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface $itemExpander
     *
     * @return void
     */
    public function addItemExpanderPlugin(ItemExpanderPluginInterface $itemExpander)
    {
        $this->itemExpanderPlugins[] = $itemExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\CartTransfer $cart
     *
     * @return \Generated\Shared\Transfer\CartTransfer
     */
    protected function getGroupedCartItems(CartTransfer $cart)
    {
        $groupAbleItems = new GroupableContainerTransfer();
        $groupAbleItems->setItems($cart->getItems());

        $groupedItems = $this->itemGrouperFacade->groupItemsByKey($groupAbleItems);

        $cart->setItems($groupedItems->getItems());

        return $cart;
    }

}
