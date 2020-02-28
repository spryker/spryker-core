<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantStockDataImport;

use Codeception\Actor;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StockTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantStockDataImportCommunicationTester extends Actor
{
    use _generated\MerchantStockDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function createMerchantStockRelatedData(): void
    {
        $this->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => 'merchant-test-reference-1']);
        $this->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => 'merchant-test-reference-2']);
        $this->haveStock([StockTransfer::NAME => 'Warehouse 1']);
        $this->haveStock([StockTransfer::NAME => 'Warehouse 2']);
    }
}
