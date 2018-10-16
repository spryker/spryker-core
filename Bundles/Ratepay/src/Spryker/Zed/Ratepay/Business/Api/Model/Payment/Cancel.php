<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Model\Payment;

use Spryker\Zed\Ratepay\Business\Api\Builder\Head;
use Spryker\Zed\Ratepay\Business\Api\Builder\ShoppingBasket;
use Spryker\Zed\Ratepay\Business\Api\Constants;
use Spryker\Zed\Ratepay\Business\Api\Model\Base;

class Cancel extends Base
{
    /**
     * @const Method operation.
     */
    public const OPERATION = Constants::REQUEST_MODEL_PAYMENT_CHANGE;

    /**
     * @const Method operation subtype.
     */
    public const OPERATION_SUBTYPE = 'cancellation';

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
        $this->getHead()->setOperationSubstring(static::OPERATION_SUBTYPE);
        $paymentRequestData = parent::buildData();
        $paymentRequestData['content'] = [
            $this->getShoppingBasket()->getRootTag() => $this->getShoppingBasket(),
        ];

        return $paymentRequestData;
    }

    /**
     * @return \Spryker\Zed\Ratepay\Business\Api\Builder\ShoppingBasket
     */
    public function getShoppingBasket()
    {
        return $this->basket;
    }
}
