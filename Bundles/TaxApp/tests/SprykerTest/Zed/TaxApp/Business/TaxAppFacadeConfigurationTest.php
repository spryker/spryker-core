<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxApp\Business;

use Codeception\Stub;
use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TaxAppConfigConditionsTransfer;
use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;
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
 * @group TaxAppFacadeConfigurationTest
 * Add your own group annotations below this line
 */
class TaxAppFacadeConfigurationTest extends Unit
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
        $storeTransfer = $this->tester->createStoreTransferWithStoreReference();
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod($storeTransfer);
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer();

        // Act
        $this->tester->getFacade()->saveTaxAppConfig($taxAppConfigTransfer);

        // Assert
        $this->tester->assertTaxAppWithVendorCodeIsConfigured($taxAppConfigTransfer->getVendorCode(), $storeTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testWhenTaxAppConfigDoesNotExistSaveTaxAppConfigWithMultipleStoresSuccessfullySavesConfig(): void
    {
        // Arrange
        $storeTransfer1 = $this->tester->haveStore([StoreTransfer::NAME => 'store1'], false);
        $storeTransfer2 = $this->tester->haveStore([StoreTransfer::NAME => 'store2'], false);
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceWithMultipleStoresMethod($storeTransfer1, $storeTransfer2);
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer();

        // Act
        $this->tester->getFacade()->saveTaxAppConfig($taxAppConfigTransfer);

        // Assert
        $this->tester->assertTaxAppWithVendorCodeIsConfiguredWithMultipleStores($taxAppConfigTransfer->getVendorCode(), [$storeTransfer1, $storeTransfer2]);
    }

    /**
     * @return void
     */
    public function testWhenTaxAppConfigDoesNotExistSaveTaxAppConfigWithTenantIdentifierSuccessfullySavesConfig(): void
    {
        // Arrange
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer([
            TaxAppConfigTransfer::TENANT_IDENTIFIER => 'tenant-identifier',
            TaxAppConfigTransfer::STORE_REFERENCE => null,
        ]);
        $storeTransfer = $this->tester->createStoreTransferWithStoreReference();

        // Act
        $this->tester->getFacade()->saveTaxAppConfig($taxAppConfigTransfer);

        // Assert
        $this->tester->assertTaxAppWithVendorCodeIsConfigured($taxAppConfigTransfer->getVendorCode(), $storeTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testWhenTaxAppConfigExistsSaveTaxAppConfigSuccessfullyUpdatesExistingConfig(): void
    {
        // Arrange
        $storeTransfer = $this->tester->createStoreTransferWithStoreReference();
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceMethod($storeTransfer);
        $vendorCode = Uuid::uuid4()->toString();
        $this->tester->haveTaxAppConfig([
            TaxAppConfigTransfer::VENDOR_CODE => $vendorCode,
            TaxAppConfigTransfer::IS_ACTIVE => false,
            'fk_store' => $storeTransfer->getIdStore(),
        ]);

        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer([
            TaxAppConfigTransfer::VENDOR_CODE => $vendorCode,
            TaxAppConfigTransfer::IS_ACTIVE => true,
        ]);

        // Act
        $this->tester->getFacade()->saveTaxAppConfig($taxAppConfigTransfer);

        // Assert
        $this->tester->assertTaxAppWithVendorCodeIsConfigured($taxAppConfigTransfer->getVendorCode(), $storeTransfer->getIdStore(), true);
    }

    /**
     * @group new
     *
     * @return void
     */
    public function testWhenTaxAppConfigExistsSaveTaxAppConfigWithTenantIdentifierSuccessfullyUpdatesExistingConfig(): void
    {
        // Arrange
        $vendorCode = Uuid::uuid4()->toString();
        $initialTaxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer([
            TaxAppConfigTransfer::VENDOR_CODE => $vendorCode,
            TaxAppConfigTransfer::TENANT_IDENTIFIER => 'tenant-identifier',
            TaxAppConfigTransfer::STORE_REFERENCE => null,
            TaxAppConfigTransfer::IS_ACTIVE => false,
        ]);

        $this->tester->getFacade()->saveTaxAppConfig($initialTaxAppConfigTransfer);

        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer([
            TaxAppConfigTransfer::VENDOR_CODE => $vendorCode,
            TaxAppConfigTransfer::TENANT_IDENTIFIER => 'tenant-identifier',
            TaxAppConfigTransfer::STORE_REFERENCE => null,
            TaxAppConfigTransfer::IS_ACTIVE => true,
        ]);
        $storeTransfer = $this->tester->createStoreTransferWithStoreReference();

        // Act
        $this->tester->getFacade()->saveTaxAppConfig($taxAppConfigTransfer);

        // Assert
        $this->tester->assertTaxAppWithVendorCodeIsConfigured($taxAppConfigTransfer->getVendorCode(), $storeTransfer->getIdStore(), true);
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
    public function testWhenTaxAppConfigExistsDeleteTaxAppConfigWithMultipleStoresIsSuccessful(): void
    {
        // Arrange
        $storeTransfer1 = $this->tester->haveStore([StoreTransfer::NAME => 'store1']);
        $storeTransfer2 = $this->tester->haveStore([StoreTransfer::NAME => 'store2']);
        $this->tester->configureStoreFacadeGetStoreByStoreReferenceWithMultipleStoresMethod($storeTransfer1, $storeTransfer2);

        $vendorCode = Uuid::uuid4()->toString();
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer([
            TaxAppConfigTransfer::VENDOR_CODE => $vendorCode,
        ]);

        $this->tester->getFacade()->saveTaxAppConfig($taxAppConfigTransfer);

        $taxAppConfigCriteriaTransfer = $this->tester->createTaxAppConfigCriteriaTransferWithTaxAppConfigConditionsTransfer([
            'vendor_codes' => [$vendorCode],
        ]);

        // Act
        $this->tester->getFacade()->deleteTaxAppConfig($taxAppConfigCriteriaTransfer);

        // Assert
        $this->tester->assertTaxAppWithVendorCodeDoesNotExist($vendorCode);
    }

    /**
     * @return void
     */
    public function testWhenTaxAppConfigExistsDeleteTaxAppConfigWithoutStoreReferenceIsSuccessful(): void
    {
        // Arrange
        $vendorCode = Uuid::uuid4()->toString();
        $taxAppConfigTransfer = $this->tester->haveTaxAppConfig(['vendor_code' => $vendorCode]);

        $taxAppConfigCriteriaTransfer = $this->tester->createTaxAppConfigCriteriaTransferWithTaxAppConfigConditionsTransfer([
            'vendor_codes' => [$taxAppConfigTransfer->getVendorCode()],
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

    /**
     * @return void
     */
    public function testWhenTaxAppConfigDoesNotExistAndStoreReferenceIsNullSaveTaxAppConfigSuccessfullySavesConfig(): void
    {
        // Arrange
        $taxAppConfigTransfer = $this->tester->createTaxAppConfigTransfer(['store_reference' => null]);

        // Act
        $this->tester->getFacade()->saveTaxAppConfig($taxAppConfigTransfer);

        // Assert
        $storeTransfer = $this->tester->createStoreTransferWithStoreReference();
        $this->tester->assertTaxAppWithVendorCodeIsConfigured($taxAppConfigTransfer->getVendorCode(), $storeTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testWhenMultipleTaxAppConfigExistsAndStoreReferenceIsNullDeleteTaxAppConfigSuccessfullyDeletesAllConfigsForTenant(): void
    {
        // Arrange
        $deletedTenant = 'tenant1';
        $notDeletedTenant = 'tenant2';
        $vendorCode = Uuid::uuid4()->toString();

        $this->tester->haveTaxAppConfig(['store_reference' => null, 'tenant_identifier' => $notDeletedTenant, 'vendor_code' => $vendorCode]);
        $this->tester->haveTaxAppConfig(['store_reference' => null, 'tenant_identifier' => $deletedTenant, 'vendor_code' => $vendorCode]);
        $this->tester->haveTaxAppConfig(['store_reference' => null, 'tenant_identifier' => $deletedTenant, 'vendor_code' => $vendorCode]);

        $taxAppConfigCriteriaTransfer = (new TaxAppConfigCriteriaTransfer())->setTaxAppConfigConditions((new TaxAppConfigConditionsTransfer())->setVendorCodes([$vendorCode]));

        // Act
        $this->tester->getFacade()->deleteTaxAppConfig($taxAppConfigCriteriaTransfer);

        // Assert
        $this->tester->assertAllTaxAppConfigsForTenantHaveBeenDeleted($vendorCode);
    }
}
