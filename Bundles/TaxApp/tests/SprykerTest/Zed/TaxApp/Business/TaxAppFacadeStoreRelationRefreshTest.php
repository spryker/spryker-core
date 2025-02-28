<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxApp\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use SprykerTest\Zed\TaxApp\TaxAppBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group TaxApp
 * @group Business
 * @group Facade
 * @group TaxAppFacadeStoreRelationRefreshTest
 * Add your own group annotations below this line
 */
class TaxAppFacadeStoreRelationRefreshTest extends Unit
{
    protected TaxAppBusinessTester $tester;

    /**
     * @return void
     */
    public function testRefreshAllTaxAppConfigStoreRelationsIfNewStoreWasAddedAppendsThisStoreIntoAssetStoreRelations(): void
    {
        if (!$this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('This test is not applicable for non-dynamic stores.');
        }

        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'test-store',
        ]);

        $taxApConfigTransfer = $this->tester->haveTaxAppConfig();

        $this->tester->assertTaxAppConfigStoreRelationDoesNotExist($taxApConfigTransfer->getApplicationId(), $storeTransfer->getIdStore());

        // Act
        $this->tester->getFacade()->refreshTaxAppStoreRelations();

        // Assert
        $this->tester->assertTaxAppConfigStoreRelationExists($taxApConfigTransfer->getApplicationId(), $storeTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testRefreshAllTaxAppConfigStoreRelationsDoesNothingToExistingRelationsIfNoStoreWasAdded(): void
    {
        if (!$this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('This test is not applicable for non-dynamic stores.');
        }

        // Arrange
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'test-store',
        ]);

        $taxApConfigTransfer = $this->tester->haveTaxAppConfig([
            'fk_store' => $storeTransfer->getIdStore(),
        ]);

        $this->tester->assertTaxAppConfigStoreRelationExists($taxApConfigTransfer->getApplicationId(), $storeTransfer->getIdStore());

        // Act
        $this->tester->getFacade()->refreshTaxAppStoreRelations();

        // Assert
        $this->tester->assertTaxAppConfigStoreRelationExists($taxApConfigTransfer->getApplicationId(), $storeTransfer->getIdStore());
    }
}
