<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxApp\Business;

use Codeception\Stub;
use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\TaxAppConfigTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\TaxApp\Business\Exception\TaxAppConfigurationCouldNotBeDeleted;
use Spryker\Zed\TaxApp\Business\Exception\TaxAppConfigurationCouldNotBeSaved;
use Spryker\Zed\TaxApp\Persistence\TaxAppEntityManagerInterface;
use SprykerTest\Zed\TaxApp\TaxAppBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group TaxApp
 * @group Business
 * @group Facade
 * @group TaxAppFacadeConfigTest
 * Add your own group annotations below this line
 */
class TaxAppFacadeConfigTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\TaxApp\TaxAppBusinessTester
     */
    protected TaxAppBusinessTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureTaxAppConfigTableIsEmpty();
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod();
    }

    /**
     * @return void
     */
    public function testWhenTaxAppConfigDoesNotExistSaveTaxAppConfigSuccessfullySavesConfig(): void
    {
        // Arrange
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer();

        // Act
        $this->tester->getFacade()->saveTaxAppConfig($taxAppConfigTransfer);

        // Assert
        $this->tester->assertTaxAppWithVendorCodeIsConfigured($taxAppConfigTransfer->getVendorCode());
    }

    /**
     * @return void
     */
    public function testWhenTaxAppConfigExistsSaveTaxAppConfigSuccessfullyUpdatesExistingConfig(): void
    {
        // Arrange
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod();
        $vendorCode = Uuid::uuid4()->toString();
        $storeTransfer = $this->tester->haveStore([], false);
        $this->tester->haveTaxAppConfig(['vendor_code' => $vendorCode, 'fk_store' => $storeTransfer->getIdStore()]);

        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer(['vendor_code' => $vendorCode]);

        // Act
        $this->tester->getFacade()->saveTaxAppConfig($taxAppConfigTransfer);

        // Assert
        $this->tester->assertTaxAppWithVendorCodeIsConfigured($taxAppConfigTransfer->getVendorCode());
    }

    /**
     * @return void
     */
    public function testWhenTaxAppConfigExistsDeleteTaxAppConfigIsSuccessful(): void
    {
        // Arrange
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod();
        $vendorCode = Uuid::uuid4()->toString();
        $storeTransfer = $this->tester->haveStore([], false);
        $taxAppConfigTransfer = $this->tester->haveTaxAppConfig(['vendor_code' => $vendorCode, 'fk_store' => $storeTransfer->getIdStore()]);

        $taxAppConfigCriteriaTransfer = $this->tester->createTaxAppConfigCriteriaTransferWithTaxAppConfigConditionsTransfer([
            'vendor_codes' => [$taxAppConfigTransfer->getVendorCode()],
            'store_references' => [$taxAppConfigTransfer->getStoreReference()],
        ]);

        // Act
        $this->tester->getFacade()->deleteTaxAppConfig($taxAppConfigCriteriaTransfer);

        // Assert
        $this->tester->assertTaxAppWithVendorCodeDoesNotExist($taxAppConfigTransfer->getVendorCode());
    }

    /**
     * @return void
     */
    public function testWhenTaxAppConfigDoesNotExistDeleteTaxAppConfigThrowsException(): void
    {
        // Arrange
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod();
        $taxAppConfigCriteriaTransfer = $this->tester->createTaxAppConfigCriteriaTransfer();

        // Assert
        $this->expectException(TaxAppConfigurationCouldNotBeDeleted::class);

        // Act
        $this->tester->getFacade()->deleteTaxAppConfig($taxAppConfigCriteriaTransfer);
    }

    /**
     * @return void
     */
    public function testWhenTaxAppConfigCouldNotPersistedAnExceptionIsThrown(): void
    {
        // Arrange
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod();
        $taxAppEntityManagerMock = Stub::makeEmpty(TaxAppEntityManagerInterface::class, [
            'saveTaxAppConfig' => function (): void {
                throw new Exception('something went wrong');
            },
        ]);
        $this->tester->mockFactoryMethod('getEntityManager', $taxAppEntityManagerMock);

        // Assert
        $this->expectException(TaxAppConfigurationCouldNotBeSaved::class);

        // Act
        $this->tester->getFacade()->saveTaxAppConfig((new TaxAppConfigTransfer())->setStoreReference('de-DE'));
    }
}
