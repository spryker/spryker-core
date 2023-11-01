<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductBundleCartsRestApi;

use Codeception\Actor;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(\SprykerTest\Glue\ProductBundleCartsRestApi\PHPMD)
 */
class ProductBundleCartsRestApiPluginTester extends Actor
{
    use _generated\ProductBundleCartsRestApiPluginTesterActions;

    /**
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $bundleItemTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(array $itemTransfers = [], array $bundleItemTransfers = []): QuoteTransfer
    {
        $quoteTransfer = new QuoteTransfer();

        foreach ($itemTransfers as $itemTransfer) {
            $quoteTransfer->addItem($itemTransfer);
        }

        foreach ($bundleItemTransfers as $itemTransfer) {
            $quoteTransfer->addBundleItem($itemTransfer);
        }

        return $quoteTransfer;
    }
}
