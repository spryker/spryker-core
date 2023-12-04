<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\TaxApp\Business;

use Codeception\Stub;
use Codeception\Test\Unit;
use Exception;
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
        $this->tester->assertTaxAppWithVendorCodeIsConfigured($taxAppConfigTransfer->getVendorCode());
    }

    /**
     * @return void
     */
    public function testWhenMultipleTaxAppConfigExistsAndStoreReferenceIsNullSaveTaxAppConfigSuccessfullyUpdatesAllConfigs(): void
    {
        // Arrange
        $tenant = 'tenant1';
        $vendorCode = Uuid::uuid4()->toString();
        $newApiUrl = 'new-api-url';

        $secondTenantConfig = $this->tester->haveTaxAppConfig(['store_reference' => null, 'tenant_identifier' => 'tenant-2', 'vendor_code' => $vendorCode]);
        $this->tester->haveTaxAppConfig(['api_url' => '1', 'store_reference' => null, 'tenant_identifier' => $tenant, 'vendor_code' => $vendorCode]);
        $this->tester->haveTaxAppConfig(['api_url' => '2', 'store_reference' => null, 'tenant_identifier' => $tenant, 'vendor_code' => $vendorCode]);
        $this->tester->haveTaxAppConfig(['api_url' => '3', 'store_reference' => null, 'tenant_identifier' => $tenant, 'vendor_code' => $vendorCode]);

        $taxAppConfigTransfer1 = $this->tester->createTaxAppConfigTransfer(['api_url' => $newApiUrl, 'store_reference' => null, 'tenant_identifier' => $tenant, 'vendor_code' => $vendorCode]);

        // Act
        $this->tester->getFacade()->saveTaxAppConfig($taxAppConfigTransfer1);

        // Assert
        $this->tester->assertAllTaxAppConfigsForTenantHaveNewApiUrl($taxAppConfigTransfer1, $newApiUrl);
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
