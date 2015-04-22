<?php

namespace SprykerFeature\Zed\Cart\Communication\Controller;

use SprykerFeature\Shared\Cart\Transfer\CartChange;
use SprykerFeature\Shared\Cart\Transfer\StepStorage;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractSdkController;
use SprykerEngine\Zed\Kernel\Business\ModelResult;

class SdkController extends AbstractSdkController
{

    /**
     * @param CartChange $cartChange
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function addItemsAction(CartChange $cartChange)
    {
        $result = $this->getCartFacade()->addItems($cartChange);

        return $this->handleCartResult($cartChange, $result, \SprykerFeature_Shared_Cart_Code_Messages::SUCCESS_PRODUCT_ADD);
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function removeItemsAction(CartChange $cartChange)
    {
        $result = $this->getCartFacade()->removeItems($cartChange);

        return $this->handleCartResult(
            $cartChange,
            $result,
            \SprykerFeature_Shared_Cart_Code_Messages::SUCCESS_PRODUCT_REMOVE
        );
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function changeQuantityAction(CartChange $cartChange)
    {
        $result = $this->getCartFacade()->changeQuantity($cartChange);

        return $this->handleCartResult(
            $cartChange,
            $result,
            \SprykerFeature_Shared_Cart_Code_Messages::SUCCESS_PRODUCT_QUANTITY_CHANGE
        );
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function mergeGuestCartWithCustomerCartAction(CartChange $cartChange)
    {
        //$order = $this->facadeCart->mergeGuestCartWithCustomerCart($cartChange);
        //$this->checkCouponCodeRemoval($cartChange, $order);
//        return $order;
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function clearCartStorageAction(CartChange $cartChange)
    {
        $order = $this->getCartFacade()->clearCartStorage($cartChange);

        return $order;
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function addCouponCodeAction(CartChange $cartChange)
    {
        $result = $this->getCartFacade()->addCouponCode($cartChange);

        return $this->handleCartResult(
            $cartChange,
            $result,
            \SprykerFeature_Shared_Cart_Code_Messages::SUCCESS_COUPON_CODE_ADD
        );
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function removeCouponCodeAction(CartChange $cartChange)
    {
        $result = $this->getCartFacade()->removeCouponCode($cartChange);

        return $this->handleCartResult(
            $cartChange,
            $result,
            \SprykerFeature_Shared_Cart_Code_Messages::SUCCESS_COUPON_CODE_REMOVE
        );
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function clearCouponCodeAction(CartChange $cartChange)
    {
        $result = $this->getCartFacade()->clearCouponCodes($cartChange);

        return $this->handleCartResult(
            $cartChange,
            $result,
            \SprykerFeature_Shared_Cart_Code_Messages::SUCCESS_COUPON_CODES_CLEAR
        );
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function recalculateAction(CartChange $cartChange)
    {
        $order = $this->getCalculationFacade()->recalculate(clone $cartChange->getOrder());
        if ($this->isCouponCodeRemoved($cartChange, $order)) {
            $this->addErrorMessage(\SprykerFeature_Shared_Cart_Code_Messages::ERROR_COUPON_CODE_REMOVED);
        }

        return $order;
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerFeature\Shared\Sales\Transfer\Order
     */
    public function loadCartByHashAction(CartChange $cartChange)
    {
//        $order = $this->facadeCart->loadGuestCartByHash($cartChange);
//
//        $this->addMessage(\SprykerFeature_Shared_Cart_Code_Messages::SUCCESS_PRODUCT_ADD);
//        $this->wasCouponCodeRemoved($cartChange, $order);

//        return $order;
    }

    /**
     * @param StepStorage $transfer
     * @return StepStorage
     */
    public function storeUserCartStepAction(StepStorage $transfer)
    {
        //return $this->facadeCart->storeUserCartStep($transfer);
    }

    /**
     * @param CartChange $cartChange
     * @param \SprykerEngine\Zed\Kernel\Business\ModelResult $result
     * @param string $successMessage
     * @return Order
     */
    private function handleCartResult(CartChange $cartChange, ModelResult $result, $successMessage)
    {
        if (!$result->isSuccess()) {
            $this->setSuccess(false);
            foreach ($result->getErrors() as $message) {
                $this->addErrorMessage($message);
            }
        } else {
            $this->addMessage($successMessage);
        }

        if ($successMessage != \SprykerFeature_Shared_Cart_Code_Messages::SUCCESS_COUPON_CODES_CLEAR &&
            $this->isCouponCodeRemoved($cartChange, $result->getTransfer())) {
            $this->addErrorMessage(\SprykerFeature_Shared_Cart_Code_Messages::ERROR_COUPON_CODE_REMOVED);
        }

        return $result->getTransfer();
    }

    /**
     * @param CartChange $cartChange
     * @param Order $order
     * @return bool
     */
    protected function isCouponCodeRemoved(CartChange $cartChange, Order $order)
    {
        foreach ($cartChange->getOrder()->getCouponCodes() as $code) {
            if (!in_array($code, $order->getCouponCodes()) && $code !== $cartChange->getCouponCode()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return \SprykerFeature\Zed\Cart\Business\CartFacade
     */
    protected function getCartFacade()
    {
        return $this->getLocator()->cart()->facade();
    }

    /**
     * @return \SprykerFeature\Zed\Calculation\Business\CalculationFacade
     */
    protected function getCalculationFacade()
    {
        return $this->getLocator()->calculation()->facade();
    }
}
