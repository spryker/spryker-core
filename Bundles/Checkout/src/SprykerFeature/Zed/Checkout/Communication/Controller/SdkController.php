<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Checkout\Communication\Controller;

use Generated\Shared\Checkout\CheckoutRequestInterface;
use Generated\Shared\Checkout\CheckoutResponseInterface;
use Generated\Shared\Transfer\OrderItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use SprykerFeature\Shared\ZedRequest\Client\RequestInterface;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractSdkController;
use SprykerFeature\Zed\Calculation\Business\CalculationFacade;
use SprykerFeature\Zed\Checkout\Business\CheckoutFacade;
use SprykerFeature\Zed\Setup\Business\Exception\NotEnoughItemsInStockException;
use SprykerFeature\Zed\Setup\Business\Exception\OrderNotNewException;
use SprykerFeature\Zed\Setup\Business\Exception\PriceChangedException;

/**
 * @method CheckoutFacade getFacade()
 */
class SdkController extends AbstractSdkController
{

    /**
     * @param CheckoutRequestInterface $checkoutRequest
     *
     * @return CheckoutResponseInterface
     */
    public function requestCheckoutAction(CheckoutRequestInterface $checkoutRequest)
    {
        try {
            $result = $this->getFacade()->requestCheckout($checkoutRequest);
        } catch (NotEnoughItemsInStockException $e) {
            $this->setSuccess(false);
            $this->addErrorMessage('error.stock.notenough');
        } catch (OrderNotNewException $e) {
            $this->setSuccess(false);
            $this->addErrorMessage('error.order.notnew');
        } catch (PriceChangedException $e) {
            $this->setSuccess(false);
            $this->addErrorMessage('error.price.different');
        }

        return $result;
    }
}
