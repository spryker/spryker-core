<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cart\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\GroupableContainerTransfer;
use Spryker\Zed\Messenger\Business\MessengerFacade;
use Spryker\Zed\Calculation\Business\CalculationFacade;
use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade;

class Operation
{
    const ADD_ITEMS_SUCCESS = 'cart.add.items.success';
    const INCREASE_ITEMS_SUCCESS = 'cart.increase.items.success';
    const REMOVE_ITEMS_SUCCESS = 'cart.remove.items.success';
    const DECREASE_ITEMS_SUCCESS = 'cart.decrease.items.success';

    /**
     * @var \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface
     */
    protected $cartStorageProvider;

    /**
     * @var \Spryker\Zed\Calculation\Business\CalculationFacade
     */
    protected $calculationFacade;

    /**
     * @var \Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade
     */
    protected $itemGrouperFacade;

    /**
     * @var \Spryker\Zed\Messenger\Business\MessengerFacade
     */
    protected $messengerFacade;

    /**
     * @var \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[]
     */
    protected $itemExpanderPlugins;

    /**
     * @param \Spryker\Zed\Cart\Business\StorageProvider\StorageProviderInterface $cartStorageProvider
     * @param \Spryker\Zed\Calculation\Business\CalculationFacade $calculationFacade
     * @param \Spryker\Zed\ItemGrouper\Business\ItemGrouperFacade $itemGrouperFacade
     * @param \Spryker\Zed\Messenger\Business\MessengerFacade $messengerFacade
     * @param \Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface[] $itemExpanderPlugins
     */
    public function __construct(
        StorageProviderInterface $cartStorageProvider,
        CalculationFacade $calculationFacade,
        ItemGrouperFacade $itemGrouperFacade,
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
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function add(CartChangeTransfer $cartChangeTransfer)
    {
        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->addItems($expandedCartChangeTransfer);
        $quoteTransfer = $this->getGroupedCartItems($quoteTransfer);
        $this->messengerFacade->addSuccessMessage(self::ADD_ITEMS_SUCCESS);

        return $this->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increase(CartChangeTransfer $cartChangeTransfer)
    {
        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->increaseItems($expandedCartChangeTransfer);
        $quoteTransfer = $this->getGroupedCartItems($quoteTransfer);
        $this->messengerFacade->addSuccessMessage(self::INCREASE_ITEMS_SUCCESS);

        return $this->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decrease(CartChangeTransfer $cartChangeTransfer)
    {
        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->decreaseItems($expandedCartChangeTransfer);
        $this->messengerFacade->addSuccessMessage(self::DECREASE_ITEMS_SUCCESS);

        return $this->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function remove(CartChangeTransfer $cartChangeTransfer)
    {
        $expandedCartChangeTransfer = $this->expandChangedItems($cartChangeTransfer);
        $quoteTransfer = $this->cartStorageProvider->removeItems($expandedCartChangeTransfer);
        $this->messengerFacade->addSuccessMessage(self::REMOVE_ITEMS_SUCCESS);

        return $this->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function expandChangedItems(CartChangeTransfer $cartChangeTransfer)
    {
        foreach ($this->itemExpanderPlugins as $itemExpander) {
            $cartChangeTransfer = $itemExpander->expandItems($cartChangeTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function recalculate(QuoteTransfer $quoteTransfer)
    {
        return $this->calculationFacade->recalculate($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
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
