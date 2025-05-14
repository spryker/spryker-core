<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeatureTest\Zed\SspAssetManagement;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface;

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
 * @SuppressWarnings(PHPMD)
 */
class SspAssetManagementCommunicationTester extends Actor
{
    use _generated\SspAssetManagementCommunicationTesterActions;

    /**
     * @var string
     */
    public const TEST_ASSET_REFERENCE = 'test-asset-reference';

    /**
     * @var string
     */
    public const TEST_ASSET_NAME = 'Test Asset';

    /**
     * @var string
     */
    public const TEST_ASSET_SERIAL_NUMBER = 'SN123456789';

    /**
     * @var string
     */
    public const TEST_ASSET_REFERENCE_2 = 'test-asset-reference-2';

    /**
     * @var string
     */
    public const TEST_ASSET_NAME_2 = 'Test Asset 2';

    /**
     * @var string
     */
    public const TEST_ASSET_SERIAL_NUMBER_2 = 'SN987654321';

    /**
     * @param string $assetReference
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeTransferWithSspAsset(string $assetReference): CartChangeTransfer
    {
        $itemTransfer = new ItemTransfer();
        $sspAssetTransfer = new SspAssetTransfer();
        $sspAssetTransfer->setReference($assetReference);
        $itemTransfer->setSspAsset($sspAssetTransfer);

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems(new ArrayObject([$itemTransfer]));
        $cartChangeTransfer->setQuote(new QuoteTransfer());

        return $cartChangeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeTransferWithoutSspAsset(): CartChangeTransfer
    {
        $itemTransfer = new ItemTransfer();

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems(new ArrayObject([$itemTransfer]));
        $cartChangeTransfer->setQuote(new QuoteTransfer());

        return $cartChangeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createEmptyCartChangeTransfer(): CartChangeTransfer
    {
        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->setItems(new ArrayObject());
        $cartChangeTransfer->setQuote(new QuoteTransfer());

        return $cartChangeTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function createSspAssetCollectionTransfer(): SspAssetCollectionTransfer
    {
        $sspAssetTransfer = new SspAssetTransfer();
        $sspAssetTransfer
            ->setReference(static::TEST_ASSET_REFERENCE)
            ->setName(static::TEST_ASSET_NAME)
            ->setSerialNumber(static::TEST_ASSET_SERIAL_NUMBER)
            ->setStatus('ACTIVE');

        $sspAssetCollectionTransfer = new SspAssetCollectionTransfer();
        $sspAssetCollectionTransfer->addSspAsset($sspAssetTransfer);

        return $sspAssetCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer|null $returnValue
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface
     */
    public function createSspAssetManagementRepositoryMock(?SspAssetCollectionTransfer $returnValue = null): MockObject
    {
        $mockBuilder = $this->getMockBuilder(SspAssetManagementRepositoryInterface::class);
        $mockBuilder->disableOriginalConstructor();

        $mock = $mockBuilder->getMock();

        if ($returnValue !== null) {
            $mock->method('getSspAssetCollection')
                ->willReturn($returnValue);
        }

        return $mock;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderTransferWithItems(): OrderTransfer
    {
        $itemTransfer1 = new ItemTransfer();
        $itemTransfer1->setIdSalesOrderItem(1);

        $itemTransfer2 = new ItemTransfer();
        $itemTransfer2->setIdSalesOrderItem(2);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setItems(new ArrayObject([$itemTransfer1, $itemTransfer2]));

        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createEmptyOrderTransfer(): OrderTransfer
    {
        $orderTransfer = new OrderTransfer();
        $orderTransfer->setItems(new ArrayObject());

        return $orderTransfer;
    }

    /**
     * @return array<int, \Generated\Shared\Transfer\SspAssetTransfer>
     */
    public function createSspAssetTransfersIndexedBySalesOrderItemId(): array
    {
        $sspAssetTransfer1 = new SspAssetTransfer();
        $sspAssetTransfer1
            ->setReference(static::TEST_ASSET_REFERENCE)
            ->setName(static::TEST_ASSET_NAME)
            ->setSerialNumber(static::TEST_ASSET_SERIAL_NUMBER)
            ->setStatus('ACTIVE');

        $sspAssetTransfer2 = new SspAssetTransfer();
        $sspAssetTransfer2
            ->setReference(static::TEST_ASSET_REFERENCE_2)
            ->setName(static::TEST_ASSET_NAME_2)
            ->setSerialNumber(static::TEST_ASSET_SERIAL_NUMBER_2)
            ->setStatus('ACTIVE');

        return [
            1 => $sspAssetTransfer1,
            2 => $sspAssetTransfer2,
        ];
    }
}
