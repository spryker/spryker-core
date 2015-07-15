<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\AvailabilityCheckoutConnector\Communication\Plugin;

use Generated\Shared\Transfer\CartItemTransfer;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Checkout\CheckoutConfig;
use SprykerFeature\Zed\AvailabilityCheckoutConnector\Communication\AvailabilityCheckoutConnectorDependencyContainer;
use SprykerFeature\Zed\Checkout\Dependency\Plugin\CheckoutPreconditionInterface;

/**
 * @method AvailabilityCheckoutConnectorDependencyContainer getDependencyContainer()
 */
class ProductsAvailablePreconditionPlugin extends AbstractPlugin implements CheckoutPreconditionInterface
{

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return bool
     */
    protected function isProductSellable($sku, $quantity)
    {
        return $this->getDependencyContainer()->getAvailabilityFacade()->isProductSellable($sku, $quantity);
    }

    /**
     * @param CheckoutRequestTransfer $checkoutRequest
     * @param CheckoutResponseTransfer $checkoutResponse
     */
    public function checkCondition(CheckoutRequestTransfer $checkoutRequest, CheckoutResponseTransfer $checkoutResponse)
    {
        $groupedItemQuantities = $this->groupItemsBySku($checkoutRequest->getCart()->getItems());

        foreach ($groupedItemQuantities as $sku => $quantity) {
            if (!$this->isProductSellable($sku, $quantity)) {
                $error = new CheckoutErrorTransfer();
                $error
                    ->setErrorCode(CheckoutConfig::ERROR_CODE_PRODUCT_UNAVAILABLE)
                    ->setMessage('product.unavailable')
                ;

                $checkoutResponse
                    ->addError($error)
                    ->setIsSuccess(false)
                ;
            }
        }
    }

    /**
     * @param \ArrayObject|CartItemTransfer[] $items
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
