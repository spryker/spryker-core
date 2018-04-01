<?php

namespace Spryker\Zed\Offer\Communication\Plugin\Cart;


use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class OfferStockExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * @param CartChangeTransfer $cartChangeTransfer
     *
     * @return CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $cartChangeTransfer)
    {

        /** @var AvailabilityFacadeInterface $availabilityFacade */
        $availabilityFacade = Locator::getInstance()->availability()->facade();
        /** @var StoreFacadeInterface $storeFacade */
        $storeFacade = Locator::getInstance()->store()->facade();

        $storeName = $cartChangeTransfer
            ->getQuote()
            ->getStore()
            ->getName();

        $storeTransfer = $storeFacade->getStoreByName($storeName);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $stock = $availabilityFacade->calculateStockForProductWithStore($itemTransfer->getSku(), $storeTransfer);
            $itemTransfer->setStock($stock);
        }

        return $cartChangeTransfer;
    }

}