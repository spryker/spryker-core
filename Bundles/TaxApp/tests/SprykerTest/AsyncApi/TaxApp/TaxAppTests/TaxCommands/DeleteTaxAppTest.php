<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\TaxApp\TaxAppTests\TaxCommands;

use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;
use SprykerTest\AsyncApi\TaxApp\AsyncApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group AsyncApi
 * @group TaxApp
 * @group TaxAppTests
 * @group TaxCommands
 * @group DeleteTaxAppTest
 * Add your own group annotations below this line
 */
class DeleteTaxAppTest extends Unit
{
    /**
     * @var \SprykerTest\AsyncApi\TaxApp\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testWhenDeleteTaxAppMessageIsReceivedThenTaxAppConfigurationIsDeleted(): void
    {
        // Arrange
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod();
        $storeTransfer = $this->tester->haveStore([], false);
        $taxAppConfigTransfer = $this->tester->haveTaxAppConfig(['vendor_code' => Uuid::uuid4()->toString(), 'fk_store' => $storeTransfer->getIdStore()]);
        $deleteTaxAppTransfer = $this->tester->haveDeleteTaxAppMessage(['vendor_code' => $taxAppConfigTransfer->getVendorCode()]);

        // Act
        $this->tester->runMessageReceiveTest($deleteTaxAppTransfer, 'tax-commands');

        // Assert
        $this->tester->assertTaxAppWithVendorCodeDoesNotExist($taxAppConfigTransfer->getVendorCode());
    }
}
