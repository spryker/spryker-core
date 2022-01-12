<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\DiscountCalculationConnector;

use Codeception\Actor;
use DateTime;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\DiscountGeneralTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\DiscountCalculationConnector\Business\DiscountCalculationConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class DiscountCalculationConnectorBusinessTester extends Actor
{
    use _generated\DiscountCalculationConnectorBusinessTesterActions;

    /**
     * @uses \Spryker\Shared\Discount\DiscountConstants::TYPE_CART_RULE
     *
     * @var string
     */
    public const TYPE_CART_RULE = 'cart_rule';

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountGeneralTransfer
     */
    public function createCartRuleDiscount(StoreTransfer $storeTransfer, CurrencyTransfer $currencyTransfer): DiscountGeneralTransfer
    {
        return $this->haveDiscount([
            DiscountGeneralTransfer::VALID_FROM => (new DateTime('yesterday'))->format('Y-m-d H:i:s'),
            DiscountGeneralTransfer::VALID_TO => (new DateTime('tomorrow'))->format('Y-m-d H:i:s'),
            DiscountGeneralTransfer::STORE_RELATION => [
                StoreRelationTransfer::ID_STORES => [
                    $storeTransfer->getIdStore(),
                ],
            ],
        ], [
            [
                MoneyValueTransfer::NET_AMOUNT => 100,
                MoneyValueTransfer::GROSS_AMOUNT => 100,
                MoneyValueTransfer::FK_CURRENCY => $currencyTransfer->getIdCurrency(),
                MoneyValueTransfer::CURRENCY => $currencyTransfer,
            ],
        ]);
    }
}
