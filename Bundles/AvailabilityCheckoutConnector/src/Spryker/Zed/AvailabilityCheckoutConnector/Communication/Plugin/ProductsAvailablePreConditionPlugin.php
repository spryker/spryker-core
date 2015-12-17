<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\AvailabilityCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Shared\Checkout\CheckoutConstants;
use Spryker\Zed\AvailabilityCheckoutConnector\Communication\AvailabilityCheckoutConnectorCommunicationFactory;
use Spryker\Zed\Checkout\Dependency\Plugin\CheckoutPreConditionInterface;

/**
 * @method AvailabilityCheckoutConnectorCommunicationFactory getFactory()
 */
class ProductsAvailablePreConditionPlugin extends AbstractPlugin implements CheckoutPreConditionInterface
{

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    protected function isProductSellable($sku, $quantity)
    {
        return $this->getFactory()->getAvailabilityFacade()->isProductSellable($sku, $quantity);
    }

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     * @param CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function checkCondition(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $groupedItemQuantities = $this->groupItemsBySku($checkoutRequest->getCart()->getItems());

        foreach ($groupedItemQuantities as $sku => $quantity) {
            if (!$this->isProductSellable($sku, $quantity)) {
                $error = new CheckoutErrorTransfer();
                $error
                    ->setErrorCode(CheckoutConstants::ERROR_CODE_PRODUCT_UNAVAILABLE)
                    ->setMessage('product.unavailable');

                $checkoutResponse
                    ->addError($error)
                    ->setIsSuccess(false);
            }
        }
    }

    /**
     * @param \ArrayObject|ItemTransfer[] $items
     *
     * @return array
     */
    private function groupItemsBySku(\ArrayObject $items)
    {
        $result = [];

        foreach ($items as $item) {
            $sku = $item->getSku();

            if (!isset($result[$sku])) {
                $result[$sku] = 0;
            }
            $result[$sku] += $item->getQuantity();
        }

        return $result;
    }

}
