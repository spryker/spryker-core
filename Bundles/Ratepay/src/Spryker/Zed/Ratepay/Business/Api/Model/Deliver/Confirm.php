<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Deliver;

use Spryker\Zed\Ratepay\Business\Api\Builder\Head;
use Spryker\Zed\Ratepay\Business\Api\Builder\ShoppingBasket;
use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\Model\Base;

class Confirm extends Base
{
    /**
     * Deliver confirmation operation.
     */
    public const OPERATION = Constants::REQUEST_MODEL_DELIVER_CONFIRM;

    /**
     * @var \Spryker\Zed\Ratepay\Business\Api\Builder\ShoppingBasket
     */
    protected $basket;

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Builder\Head $head
     * @param \Spryker\Zed\Ratepay\Business\Api\Builder\ShoppingBasket $shoppingBasket
     */
    public function __construct(Head $head, ShoppingBasket $shoppingBasket)
    {
        parent::__construct($head);
        $this->basket = $shoppingBasket;
    }

    /**
     * @return array
     */
    protected function buildData()
    {
        $this->getHead()->setOperation(static::OPERATION);
        $paymentRequestData = parent::buildData();
        $paymentRequestData['content'] = [
            $this->getShoppingBasket()->getRootTag() => $this->getShoppingBasket(),
        ];

        return $paymentRequestData;
    }

    /**
     * @param \Spryker\Zed\Ratepay\Business\Api\Builder\ShoppingBasket $basket
     *
     * @return $this
     */
    public function setShoppingBasket(ShoppingBasket $basket)
    {
        $this->basket = $basket;
        return $this;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\ShoppingBasket
     */
    public function getShoppingBasket()
    {
        return $this->basket;
    }
}
