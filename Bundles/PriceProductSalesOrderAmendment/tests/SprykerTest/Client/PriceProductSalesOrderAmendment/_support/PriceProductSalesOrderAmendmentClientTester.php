<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PriceProductSalesOrderAmendment;

use Codeception\Actor;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentClientInterface;

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
 *
 * @SuppressWarnings(PHPMD)
 */
class PriceProductSalesOrderAmendmentClientTester extends Actor
{
    use _generated\PriceProductSalesOrderAmendmentClientTesterActions;

    /**
     * @return \Spryker\Client\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentClientInterface
     */
    public function getClient(): PriceProductSalesOrderAmendmentClientInterface
    {
        return $this->getLocator()->priceProductSalesOrderAmendment()->client();
    }

    /**
     * @param array $pricesIndexedBySku
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function prepareQuoteWithOriginalSalesOrderItemPrices(array $pricesIndexedBySku): QuoteTransfer
    {
        $quoteTransfer = (new QuoteTransfer())
            ->setAmendmentOrderReference('some-order-reference');

        foreach ($pricesIndexedBySku as $sku => $price) {
            $quoteTransfer->addOriginalSalesOrderItemUnitPrice(
                $sku,
                $price,
            );
        }

        return $quoteTransfer;
    }
}
