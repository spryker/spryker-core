<?php

namespace SprykerFeature\Zed\Cart\Business\Model;

use SprykerFeature\Shared\Cart\Transfer\CartChange;
use SprykerEngine\Zed\Kernel\Business\ModelResult;

class CouponCode
{

    /**
     * @param CartChange $cartChange
     * @return \SprykerEngine\Zed\Kernel\Business\ModelResult
     */
    public function addCouponCode(CartChange $cartChange)
    {
        if (!$this->facadeSalesrule->canUseCouponCode($cartChange->getCouponCode())) {
            $result = new \SprykerEngine\Zed\Kernel\Business\ModelResult();
            $result->addError(\SprykerFeature_Shared_Cart_Code_Messages::ERROR_COUPON_CODE_ADD);
            $result->setTransfer($cartChange->getOrder());

            return $result;
        }

        $order = clone $cartChange->getOrder();

        $order->addCouponCode($cartChange->getCouponCode());
        $recalculatedOrder = $this->facadeCalculation->recalculateOrder($order);

        $result = new \SprykerEngine\Zed\Kernel\Business\ModelResult();
        $result->setTransfer($recalculatedOrder);

        foreach ($recalculatedOrder->getCouponCodes() as $code) {
            if ($code === $cartChange->getCouponCode()) {
                return $result;
            }
        }
        $result->addError(\SprykerFeature_Shared_Cart_Code_Messages::ERROR_COUPON_CODE_ADD);

        return $result;
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerEngine\Zed\Kernel\Business\ModelResult
     */
    public function removeCouponCode(CartChange $cartChange)
    {
        $order = clone $cartChange->getOrder();

        $codes = $order->getCouponCodes();
        foreach ($codes as $key => $code) {
            if ($code === $cartChange->getCouponCode()) {
                unset($codes[$key]);
            }
        }
        $order->setCouponCodes($codes);

        $recalculatedOrder = $this->facadeCalculation->recalculateOrder($order);

        $result = new ModelResult();
        $result->setTransfer($recalculatedOrder);

        return $result;
    }

    /**
     * @param CartChange $cartChange
     * @return \SprykerEngine\Zed\Kernel\Business\ModelResult
     */
    public function clearCouponCodes(CartChange $cartChange)
    {
        $order = clone $cartChange->getOrder();
        $order->setCouponCodes([]);

        $recalculatedOrder = $this->facadeCalculation->recalculateOrder($order);

        $result = new ModelResult();
        $result->setTransfer($recalculatedOrder);

        return $result;
    }
}
