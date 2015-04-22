<?php

namespace SprykerFeature\Zed\Cart\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CartBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;

/**
 * Class CartDependencyContainer
 * @package SprykerFeature\Zed\Cart\Business
 */
/**
 * @method CartBusiness getFactory()
 */
class CartDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return Model\Cart
     */
    public function createCartModel()
    {
        return $this->getFactory()->createModelCart($this->getLocator());
    }

    /**
     * @return Model\CouponCode
     */
    public function createCouponCodeModel()
    {
        return $this->getFactory()->createModelCouponCode();
    }
}
