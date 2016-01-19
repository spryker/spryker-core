<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\GroupableContainerTransfer;
use Spryker\Zed\Messenger\Business\MessengerFacade;
use Spryker\Zed\Calculation\Business\CalculationFacade;
use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade;
use Spryker\Zed\Cart\Dependency\Facade\CartToCalculationInterface;
use Spryker\Zed\Cart\Dependency\Facade\CartToItemGrouperInterface;

class Operation
{

    const ADD_ITEMS_SUCCESS = 'cart.add.items.success';
    const INCREASE_ITEMS_SUCCESS = 'cart.increase.items.success';
    const REMOVE_ITEMS_SUCCESS = 'cart.remove.items.success';
    const DECREASE_ITEMS_SUCCESS = 'cart.decrease.items.success';

    /**
     * @var StorageProviderInterface
     */
    protected $cartStorageProvider;

    /**
     * @var CalculationFacade
     */
    protected $calculationFacade;

    /**
     * @var ItemGrouperFacade
     */
    protected $itemGrouperFacade;

    /**
     * @var MessengerFacade
     */
    protected $messengerFacade;

    /**
     * @var ItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins;

    /**
     * @param StorageProviderInterface $cartStorageProvider
     * @param CartToCalculationInterface $calculationFacade
     * @param CartToItemGrouperInterface $itemGrouperFacade
     * @param MessengerFacade $messengerFacade
     * @param ItemExpanderPluginInterface[] $itemExpanderPlugins
     */
    public function __construct(
        StorageProviderInterface $cartStorageProvider,
        CartToCalculationInterface $calculationFacade,
        CartToItemGrouperInterface $itemGrouperFacade,
        MessengerFacade $messengerFacade,
        array $itemExpanderPlugins
    ) {
        $this->cartStorageProvider = $cartStorageProvider;
        $this->calculationFacade = $calculationFacade;
        $this->itemGrouperFacade = $itemGrouperFacade;
        $this->messengerFacade = $messengerFacade;
        $this->itemExpanderPlugins = $itemExpanderPlugins;
    }

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function add(CartChangeTransfer $cartChangeTransfer)
    {
        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->addItems($expandedCartChangeTransfer);
        $quoteTransfer = $this->getGroupedCartItems($quoteTransfer);
        $this->messengerFacade->addSuccessMessage($this->createMessengerMessageTransfer(self::ADD_ITEMS_SUCCESS));

        return $this->recalculate($quoteTransfer);
    }

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function increase(CartChangeTransfer $cartChangeTransfer)
    {
        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->increaseItems($expandedCartChangeTransfer);
        $quoteTransfer = $this->getGroupedCartItems($quoteTransfer);
        $this->messengerFacade->addSuccessMessage($this->createMessengerMessageTransfer(self::INCREASE_ITEMS_SUCCESS));

        return $this->recalculate($quoteTransfer);
    }

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function decrease(CartChangeTransfer $cartChangeTransfer)
    {
        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->decreaseItems($expandedCartChangeTransfer);
        $this->messengerFacade->addSuccessMessage($this->createMessengerMessageTransfer(self::DECREASE_ITEMS_SUCCESS));

        return $this->recalculate($quoteTransfer);
    }

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return QuoteTransfer
     */
    public function remove(CartChangeTransfer $cartChangeTransfer)
    {
        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->removeItems($expandedCartChangeTransfer);
        $this->messengerFacade->addSuccessMessage($this->createMessengerMessageTransfer(self::REMOVE_ITEMS_SUCCESS));

        return $this->recalculate($quoteTransfer);
    }

    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return CartChangeTransfer
     */
    protected function expandChangedItems(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($this->itemExpanderPlugins as $itemExpander) {
            $cartChangeTransfer = $itemExpander->expandItems($cartChangeTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param string $message
     *
     * @return MessageTransfer
     */
    protected function createMessengerMessageTransfer($message)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($message);
        $messageTransfer->setParameters([]);

        return $messageTransfer;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer
     */
    protected function recalculate(QuoteTransfer $quoteTransfer)
    {
        return $this->calculationFacade->recalculate($quoteTransfer);
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return QuoteTransfer
     */
    protected function getGroupedCartItems(QuoteTransfer $quoteTransfer)
    {
        $groupAbleItems = new GroupableContainerTransfer();
        $groupAbleItems->setItems($quoteTransfer->getItems());

        $groupedItems = $this->itemGrouperFacade->groupItemsByKey($groupAbleItems);

        $quoteTransfer->setItems($groupedItems->getItems());

        return $quoteTransfer;
    }

}
