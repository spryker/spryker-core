<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Asset\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Asset\AssetDependencyProvider;
use Spryker\Zed\Asset\Business\AssetBusinessFactory;
use Spryker\Zed\Asset\Business\AssetFacadeInterface;
use Spryker\Zed\Asset\Business\Exception\InvalidAssetException;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException;
use SprykerTest\Zed\Asset\AssetBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Asset
 * @group Business
 * @group Facade
 * @group AssetFacadeTest
 * Add your own group annotations below this line
 */
class AssetFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const ASSSET_SLOT_SLT_FOOTER = 'slt-footer';

    /**
     * @var string
     */
    protected $tenantIdentifier;

    /**
     * @var string
     */
    protected $assetUuid;

    /**
     * @var \SprykerTest\Zed\Asset\AssetBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->assetUuid = $this->tester->getUuid();
        $this->tester->setStoreReferenceData(['DE' => AssetBusinessTester::STORE_REFERENCE]);
    }

    /**
     * @return void
     */
    public function testAddAssetAssertThrowsExceptionWhenStoreReferenceIsInvalid(): void
    {
        // Arrange
        $assetAddedTransfer = $this->tester->buildAssetAddedTransfer(
            '1',
            'test',
            $this->assetUuid,
        );

        // Assert
        $this->expectException(StoreReferenceNotFoundException::class);

        // Act
        $this->getAssetFacade()->addAsset($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetAssertThrowsExceptionWhenAssetIsAlreadyExist(): void
    {
        // Arrange
        $assetAddedTransfer = $this->tester->buildAssetAddedTransfer(
            AssetBusinessTester::STORE_REFERENCE,
            'test',
            $this->assetUuid,
        );
        $this->getAssetFacade()->addAsset($assetAddedTransfer);

        // Assert
        $this->expectException(InvalidAssetException::class);

        // Act
        $this->getAssetFacade()->addAsset($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetAssertSuccessfull(): void
    {
        // Arrange
        $assetMessageTransfer = $this->tester->buildAssetAddedTransfer(
            AssetBusinessTester::STORE_REFERENCE,
            static::ASSSET_SLOT_SLT_FOOTER,
            $this->assetUuid,
        );
        $expectedAssetTransfer = $this->tester->buildAssetTransfer(
            '<script>',
            $this->assetUuid,
        );

        // Act
        $assetTransfer = $this->getAssetFacade()->addAsset($assetMessageTransfer);
        $assetTransfer->setIdAsset(1)->setAssetSlot(static::ASSSET_SLOT_SLT_FOOTER);

        // Assert
        $this->assertEquals($expectedAssetTransfer, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetAssertThrowsExceptionWhenStoreReferenceIsInvalid(): void
    {
        // Arrange
        $assetUpdatedTransfer = $this->tester->buildAssetUpdatedTransfer('1');

        // Assert
        $this->expectException(StoreReferenceNotFoundException::class);

        // Act
        $this->getAssetFacade()->updateAsset($assetUpdatedTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetAssertSuccessfull(): void
    {
        // Arrange
        $startAssetMessageTransfer = $this->tester->buildAssetAddedTransfer(
            AssetBusinessTester::STORE_REFERENCE,
            static::ASSSET_SLOT_SLT_FOOTER,
            $this->assetUuid,
        );
        $newAssetMessageTransfer = $this->tester->buildAssetUpdatedTransfer(
            AssetBusinessTester::STORE_REFERENCE,
            static::ASSSET_SLOT_SLT_FOOTER,
            $this->assetUuid,
            '<script> </script>',
        );
        $expectedAssetTransfer = $this->tester->buildAssetTransfer(
            '<script> </script>',
            $this->assetUuid,
        );

        // Act
        $assetFacade = $this->getAssetFacade();
        $assetFacade->addAsset($startAssetMessageTransfer);
        $assetTransfer = $assetFacade->updateAsset($newAssetMessageTransfer);
        $assetTransfer->setIdAsset(1)->setAssetSlot(static::ASSSET_SLOT_SLT_FOOTER);

        // Assert
        $this->assertEquals($expectedAssetTransfer, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetAssertThrowsExceptionWhenStoreReferenceIsInvalid(): void
    {
        // Arrange
        $assetDeletedTransfer = $this->tester->buildAssetDeletedTransfer('1');

        // Assert
        $this->expectException(StoreReferenceNotFoundException::class);

        // Act
        $this->getAssetFacade()->deleteAsset($assetDeletedTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetAssertSuccessfull(): void
    {
        // Arrange
        $startAssetTransfer = $this->tester->buildAssetAddedTransfer(
            AssetBusinessTester::STORE_REFERENCE,
            static::ASSSET_SLOT_SLT_FOOTER,
            $this->assetUuid,
        );
        $delAssetTransfer = $this->tester->buildAssetDeletedTransfer(
            AssetBusinessTester::STORE_REFERENCE,
            $this->assetUuid,
        );
        $updateCheckTransfer = $this->tester->buildAssetUpdatedTransfer(
            AssetBusinessTester::STORE_REFERENCE,
            static::ASSSET_SLOT_SLT_FOOTER,
            $this->assetUuid,
        );

        // Assert
        $this->expectException(InvalidAssetException::class);

        // Act
        $assetFacade = $this->getAssetFacade();
        $assetFacade->addAsset($startAssetTransfer);
        $assetFacade->deleteAsset($delAssetTransfer);
        $assetFacade->updateAsset($updateCheckTransfer);
    }

    /**
     * @return void
     */
    public function testFindAssetById(): void
    {
        // Arrange
        $expectedAsset = $this->tester->haveAsset(
            ['assetSlot' => 'header'],
        );

        // Act
        $asset = $this->getAssetFacade()->findAssetById($expectedAsset->getIdAsset());

        // Assert
        $this->assertEquals($expectedAsset, $asset);
    }

    /**
     * @return \Spryker\Zed\Asset\Business\AssetFacadeInterface
     */
    protected function getAssetFacade(): AssetFacadeInterface
    {
        /** @var \Spryker\Zed\Asset\Business\AssetFacadeInterface $assetFacade */
        $assetFacade = $this->tester->getFacade();

        $container = new Container();
        $assetBusinessFactory = new AssetBusinessFactory();
        $dependencyProvider = new AssetDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $assetBusinessFactory->setContainer($container);
        $assetFacade->setFactory($assetBusinessFactory);

        return $assetFacade;
    }
}
