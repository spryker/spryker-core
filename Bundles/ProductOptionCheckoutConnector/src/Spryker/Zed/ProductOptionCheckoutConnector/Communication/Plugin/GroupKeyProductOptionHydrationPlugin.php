<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOptionCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutOrderHydrationInterface;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductOptionCheckoutConnector\Business\ProductOptionCheckoutConnectorFacade;
use Spryker\Zed\ProductOptionCheckoutConnector\Communication\ProductOptionCheckoutConnectorCommunicationFactory;

/**
 * @method ProductOptionCheckoutConnectorFacade getFacade()
 * @method ProductOptionCheckoutConnectorCommunicationFactory getFactory()
 */
class GroupKeyProductOptionHydrationPlugin extends AbstractPlugin implements CheckoutOrderHydrationInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutRequestTransfer $checkoutRequest
     *
     * @return void
     */
    public function hydrateOrder(OrderTransfer $orderTransfer, CheckoutRequestTransfer $checkoutRequest)
    {
        foreach ($orderTransfer->getItems() as $orderItem) {
            $orderItem->setGroupKey($this->buildGroupKey($orderItem));
        }
    }

    /**
     * @param ItemTransfer $item
     *
     * @return string
     */
    protected function buildGroupKey(ItemTransfer $item)
    {
        $currentGroupKey = $item->getGroupKey();
        if (empty($item->getProductOptions())) {
            return $currentGroupKey;
        }

        $sortedProductOptions = $this->sortOptions((array) $item->getProductOptions());
        $optionGroupKey = $this->combineOptionParts($sortedProductOptions);

        if (empty($optionGroupKey)) {
            return $currentGroupKey;
        }

        return !empty($currentGroupKey) ? $currentGroupKey . '-' . $optionGroupKey : $optionGroupKey;
    }

    /**
     * @param ProductOptionTransfer[] $options
     *
     * @return array
     */
    protected function sortOptions(array $options)
    {
        usort(
            $options,
            function (ProductOptionTransfer $productOptionLeft, ProductOptionTransfer $productOptionRight) {
                return ($productOptionLeft->getIdOptionValueUsage() < $productOptionRight->getIdOptionValueUsage()) ? -1 : 1;
            }
        );

        return $options;
    }

    /**
     * @param ProductOptionTransfer[] $sortedProductOptions
     *
     * @return string
     */
    protected function combineOptionParts(array $sortedProductOptions)
    {
        $groupKeyPart = [];
        foreach ($sortedProductOptions as $option) {
            if (empty($option->getIdOptionValueUsage())) {
                continue;
            }

            $groupKeyPart[] = $option->getIdOptionValueUsage();
        }

        return implode('-', $groupKeyPart);
    }

}
