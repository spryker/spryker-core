<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Client\SelfServicePortal;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\ProductListStorageTransfer;
use Generated\Shared\Transfer\SspAssetStorageCollectionTransfer;
use Generated\Shared\Transfer\SspAssetStorageConditionsTransfer;
use Generated\Shared\Transfer\SspAssetStorageCriteriaTransfer;
use Generated\Shared\Transfer\SspModelStorageCollectionTransfer;
use Generated\Shared\Transfer\SspModelStorageConditionsTransfer;
use Generated\Shared\Transfer\SspModelStorageCriteriaTransfer;
use Generated\Shared\Transfer\SspModelStorageTransfer;
use Spryker\Client\CompanyRole\Plugin\PermissionStoragePlugin;
use Spryker\Client\Permission\PermissionDependencyProvider;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalDependencyProvider;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewBusinessUnitSspAssetPermissionPlugin;
use SprykerFeature\Shared\SelfServicePortal\Plugin\Permission\ViewCompanySspAssetPermissionPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerFeatureTest
 * @group Client
 * @group SelfServicePortal
 * @group SelfServicePortalClientTest
 * Add your own group annotations below this line
 */
class SelfServicePortalClientTest extends Unit
{
    /**
     * @var int
     */
    protected const TEST_SSP_MODEL_ID_1 = 1;

    /**
     * @var int
     */
    protected const TEST_SSP_MODEL_ID_2 = 2;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_LIST_ID_1 = 10;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_LIST_ID_2 = 20;

    /**
     * @var int
     */
    protected const TEST_PRODUCT_LIST_ID_3 = 333;

    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var \SprykerFeatureTest\Client\SelfServicePortal\SelfServicePortalClientTester
     */
    protected SelfServicePortalClientTester $tester;

    public function testGetSspModelStorageCollectionReturnsEmptyCollectionWhenConditionsAreNull(): void
    {
        // Arrange
        $criteriaTransfer = new SspModelStorageCriteriaTransfer();

        // Act
        $collectionTransfer = $this->tester->getClient()->getSspModelStorageCollection($criteriaTransfer);

        // Assert
        $this->assertInstanceOf(SspModelStorageCollectionTransfer::class, $collectionTransfer);
        $this->assertCount(0, $collectionTransfer->getSspModelStorages());
    }

    public function testGetSspModelStorageCollectionReturnsEmptyCollectionWhenModelIdsAreEmpty(): void
    {
        // Arrange
        $conditionsTransfer = (new SspModelStorageConditionsTransfer())
            ->setSspModelIds([]);

        $criteriaTransfer = (new SspModelStorageCriteriaTransfer())
            ->setSspModelStorageConditions($conditionsTransfer);

        // Act
        $collectionTransfer = $this->tester->getClient()->getSspModelStorageCollection($criteriaTransfer);

        // Assert
        $this->assertInstanceOf(SspModelStorageCollectionTransfer::class, $collectionTransfer);
        $this->assertCount(0, $collectionTransfer->getSspModelStorages());
    }

    public function testGetSspModelStorageCollectionReturnsEmptyCollectionWhenNoStorageDataExists(): void
    {
        // Arrange
        $storageClientMock = $this->createMock(StorageClientInterface::class);
        $storageClientMock->method('getMulti')->willReturn([]);

        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $conditionsTransfer = (new SspModelStorageConditionsTransfer())
            ->setSspModelIds([static::TEST_SSP_MODEL_ID_1, static::TEST_SSP_MODEL_ID_2]);

        $criteriaTransfer = (new SspModelStorageCriteriaTransfer())
            ->setSspModelStorageConditions($conditionsTransfer);

        // Act
        $collectionTransfer = $this->tester->getClient()->getSspModelStorageCollection($criteriaTransfer);

        // Assert
        $this->assertInstanceOf(SspModelStorageCollectionTransfer::class, $collectionTransfer);
        $this->assertCount(0, $collectionTransfer->getSspModelStorages());
    }

    public function testGetSspModelStorageCollectionReturnsCollectionWithStorageData(): void
    {
        // Arrange
        $storageClientMock = $this->createMock(StorageClientInterface::class);
        $storageClientMock->method('getMulti')->willReturn([
            'fake_storage_key_1' => json_encode([
                'id_model' => static::TEST_SSP_MODEL_ID_1,
                'whitelist_ids' => [static::TEST_PRODUCT_LIST_ID_1, static::TEST_PRODUCT_LIST_ID_2],
            ]),
            'fake_storage_key_2' => json_encode([
                'id_model' => static::TEST_SSP_MODEL_ID_2,
                'whitelist_ids' => [static::TEST_PRODUCT_LIST_ID_3],
            ]),
        ]);

        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $conditionsTransfer = (new SspModelStorageConditionsTransfer())
            ->setSspModelIds([static::TEST_SSP_MODEL_ID_1, static::TEST_SSP_MODEL_ID_2]);

        $criteriaTransfer = (new SspModelStorageCriteriaTransfer())
            ->setSspModelStorageConditions($conditionsTransfer);

        // Act
        $collectionTransfer = $this->tester->getClient()->getSspModelStorageCollection($criteriaTransfer);

        // Assert
        $this->assertInstanceOf(SspModelStorageCollectionTransfer::class, $collectionTransfer);
        $this->assertCount(2, $collectionTransfer->getSspModelStorages());

        $sspModelStorageTransfers = $collectionTransfer->getSspModelStorages()->getArrayCopy();

        $firstModelStorage = $sspModelStorageTransfers[0];
        $this->assertInstanceOf(SspModelStorageTransfer::class, $firstModelStorage);
        $this->assertSame(static::TEST_SSP_MODEL_ID_1, $firstModelStorage->getIdModel());
        $this->assertCount(2, $firstModelStorage->getWhitelists());

        $whitelists = $firstModelStorage->getWhitelists()->getArrayCopy();
        $this->assertInstanceOf(ProductListStorageTransfer::class, $whitelists[0]);
        $this->assertSame(static::TEST_PRODUCT_LIST_ID_1, $whitelists[0]->getIdProductList());
        $this->assertInstanceOf(ProductListStorageTransfer::class, $whitelists[1]);
        $this->assertSame(static::TEST_PRODUCT_LIST_ID_2, $whitelists[1]->getIdProductList());

        $secondModelStorage = $sspModelStorageTransfers[1];
        $this->assertInstanceOf(SspModelStorageTransfer::class, $secondModelStorage);
        $this->assertSame(static::TEST_SSP_MODEL_ID_2, $secondModelStorage->getIdModel());
        $this->assertCount(1, $secondModelStorage->getWhitelists());

        $whitelists = $secondModelStorage->getWhitelists()->getArrayCopy();
        $this->assertInstanceOf(ProductListStorageTransfer::class, $whitelists[0]);
        $this->assertSame(static::TEST_PRODUCT_LIST_ID_3, $whitelists[0]->getIdProductList());
    }

    public function testGetSspModelStorageCollectionReturnsCollectionWithSingleModelId(): void
    {
        // Arrange
        $storageClientMock = $this->createMock(StorageClientInterface::class);
        $storageClientMock->method('getMulti')->willReturn([
            'fake_storage_key_1' => json_encode([
                'id_model' => static::TEST_SSP_MODEL_ID_1,
                'whitelist_ids' => [static::TEST_PRODUCT_LIST_ID_1],
            ]),
        ]);

        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $conditionsTransfer = (new SspModelStorageConditionsTransfer())
            ->setSspModelIds([static::TEST_SSP_MODEL_ID_1]);

        $criteriaTransfer = (new SspModelStorageCriteriaTransfer())
            ->setSspModelStorageConditions($conditionsTransfer);

        // Act
        $collectionTransfer = $this->tester->getClient()->getSspModelStorageCollection($criteriaTransfer);

        // Assert
        $this->assertInstanceOf(SspModelStorageCollectionTransfer::class, $collectionTransfer);
        $this->assertCount(1, $collectionTransfer->getSspModelStorages());

        $sspModelStorageTransfer = $collectionTransfer->getSspModelStorages()->getArrayCopy()[0];
        $this->assertInstanceOf(SspModelStorageTransfer::class, $sspModelStorageTransfer);
        $this->assertSame(static::TEST_SSP_MODEL_ID_1, $sspModelStorageTransfer->getIdModel());
        $this->assertCount(1, $sspModelStorageTransfer->getWhitelists());

        $whitelists = $sspModelStorageTransfer->getWhitelists()->getArrayCopy();
        $this->assertInstanceOf(ProductListStorageTransfer::class, $whitelists[0]);
        $this->assertSame(static::TEST_PRODUCT_LIST_ID_1, $whitelists[0]->getIdProductList());
    }

    public function testGetSspModelStorageCollectionHandlesEmptyWhitelistIds(): void
    {
        // Arrange
        $storageClientMock = $this->createMock(StorageClientInterface::class);
        $storageClientMock->method('getMulti')->willReturn([
            'fake_storage_key_1' => json_encode([
                'id_model' => static::TEST_SSP_MODEL_ID_1,
                'whitelist_ids' => [],
            ]),
        ]);

        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $conditionsTransfer = (new SspModelStorageConditionsTransfer())
            ->setSspModelIds([static::TEST_SSP_MODEL_ID_1]);

        $criteriaTransfer = (new SspModelStorageCriteriaTransfer())
            ->setSspModelStorageConditions($conditionsTransfer);

        // Act
        $collectionTransfer = $this->tester->getClient()->getSspModelStorageCollection($criteriaTransfer);

        // Assert
        $this->assertInstanceOf(SspModelStorageCollectionTransfer::class, $collectionTransfer);
        $this->assertCount(1, $collectionTransfer->getSspModelStorages());

        $sspModelStorageTransfer = $collectionTransfer->getSspModelStorages()->getArrayCopy()[0];
        $this->assertInstanceOf(SspModelStorageTransfer::class, $sspModelStorageTransfer);
        $this->assertSame(static::TEST_SSP_MODEL_ID_1, $sspModelStorageTransfer->getIdModel());
        $this->assertEmpty($sspModelStorageTransfer->getWhitelists());
    }

    public function testGetSspModelStorageCollectionHandlesMissingWhitelistIds(): void
    {
        // Arrange
        $storageClientMock = $this->createMock(StorageClientInterface::class);
        $storageClientMock->method('getMulti')->willReturn([
            'fake_storage_key_1' => json_encode([
                'id_model' => static::TEST_SSP_MODEL_ID_1,
            ]),
        ]);

        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_STORAGE, $storageClientMock);

        $conditionsTransfer = (new SspModelStorageConditionsTransfer())
            ->setSspModelIds([static::TEST_SSP_MODEL_ID_1]);

        $criteriaTransfer = (new SspModelStorageCriteriaTransfer())
            ->setSspModelStorageConditions($conditionsTransfer);

        // Act
        $collectionTransfer = $this->tester->getClient()->getSspModelStorageCollection($criteriaTransfer);

        // Assert
        $this->assertInstanceOf(SspModelStorageCollectionTransfer::class, $collectionTransfer);
        $this->assertCount(1, $collectionTransfer->getSspModelStorages());

        $sspModelStorageTransfer = $collectionTransfer->getSspModelStorages()->getArrayCopy()[0];
        $this->assertInstanceOf(SspModelStorageTransfer::class, $sspModelStorageTransfer);
        $this->assertSame(static::TEST_SSP_MODEL_ID_1, $sspModelStorageTransfer->getIdModel());
        $this->assertEmpty($sspModelStorageTransfer->getWhitelists());
    }

    public function testGetSspAssetStorageCollectionReturnsThrowsExceptionWithInvalidCriteriaTransfer(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserWithAssetViewPermissions();
        $this->mockStorageClientWithAssetsData($companyUserTransfer);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $collectionTransfer = $this->tester->getClient()->getSspAssetStorageCollection(
            (new SspAssetStorageCriteriaTransfer()),
        );
    }

    public function testGetSspAssetStorageCollectionFiltersAssetsBasedOnBusinessUnitPermissions(): void
    {
        // Arrange
        $companyUserTransfer = $this->createCompanyUserWithAssetViewPermissions();
        $this->mockStorageClientWithAssetsData($companyUserTransfer);

        // Act
        $collectionTransfer = $this->tester->getClient()->getSspAssetStorageCollection(
            (new SspAssetStorageCriteriaTransfer())
                ->setSspAssetStorageConditions(
                    (new SspAssetStorageConditionsTransfer())
                        ->setReferences(['reference-mock']),
                )
                ->setCompanyUser($companyUserTransfer),
        );

        // Assert
        $this->assertInstanceOf(SspAssetStorageCollectionTransfer::class, $collectionTransfer);
        $this->assertCount(2, $collectionTransfer->getSspAssetStorages());
    }

    protected function mockStorageClientWithAssetsData(CompanyUserTransfer $companyUserTransfer): void
    {
        $storageClientMock = $this->createMock(StorageClientInterface::class);

        $asset1 = $this->tester->haveAsset();
        $asset2 = $this->tester->haveAsset();
        $asset3 = $this->tester->haveAsset();

        $storageClientMock->method('getMulti')->willReturn([
            'storage_key_1' => json_encode([
                'id_asset' => $asset1->getIdSspAsset(),
                'name' => $asset1->getName(),
                'serial_number' => $asset1->getSerialNumber(),
                'reference' => $asset1->getReference(),
                'business_unit_ids' => [
                    $companyUserTransfer->getFkCompanyBusinessUnit(),
                ],
                'company_ids' => [$companyUserTransfer->getCompany()->getIdCompany()],
                'id_owner_business_unit' => 999,
                'id_owner_company_id' => 999,
            ]),
            'storage_key_2' => json_encode([
                'id_asset' => $asset2->getIdSspAsset(),
                'name' => $asset2->getName(),
                'serial_number' => $asset2->getSerialNumber(),
                'reference' => $asset2->getReference(),
                'id_owner_business_unit' => 999,
                'id_owner_company_id' => 999,
            ]),
            'storage_key_3' => json_encode([
                'id_asset' => $asset3->getIdSspAsset(),
                'name' => $asset3->getName(),
                'serial_number' => $asset3->getSerialNumber(),
                'reference' => $asset3->getReference(),
                'id_owner_business_unit' => $companyUserTransfer->getFkCompanyBusinessUnit(),
                'id_owner_company_id' => $companyUserTransfer->getCompany()->getIdCompany(),
            ]),
        ]);

        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_STORAGE, $storageClientMock);
    }

    protected function createCompanyUserWithAssetViewPermissions(): CompanyUserTransfer
    {
        $permissions = [
            new ViewCompanySspAssetPermissionPlugin(),
            new ViewBusinessUnitSspAssetPermissionPlugin(),
        ];

        $permissionTransfer1 = $this->tester->havePermission(new ViewCompanySspAssetPermissionPlugin());
        $permissionTransfer2 = $this->tester->havePermission(new ViewBusinessUnitSspAssetPermissionPlugin());

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, $permissions);

        $this->tester->getLocator()->permission()->facade()->syncPermissionPlugins();

        $companyUserTransfer = $this->tester->haveCompanyUserWithPermissions(
            $this->tester->haveCompany(),
            (new PermissionCollectionTransfer())->setPermissions(new ArrayObject([$permissionTransfer1, $permissionTransfer2])),
        );

        $criteriaTransfer = (new SspAssetStorageCriteriaTransfer())
            ->setSspAssetStorageConditions((new SspAssetStorageConditionsTransfer()))
            ->setCompanyUser($companyUserTransfer);

        $permissionPluginMock = $this->createMock(PermissionStoragePlugin::class);
        $permissionPluginMock->method('getPermissionCollection')
            ->willReturn((new PermissionCollectionTransfer())->setPermissions(new ArrayObject([$permissionTransfer1, $permissionTransfer2])));

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [
            $permissionPluginMock,
        ]);

        return $companyUserTransfer;
    }
}
