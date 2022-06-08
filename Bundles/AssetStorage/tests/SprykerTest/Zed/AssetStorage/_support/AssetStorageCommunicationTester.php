<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetStorage;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\AssetTransfer;
use Orm\Zed\AssetStorage\Persistence\SpyAssetSlotStorageQuery;
use Spryker\Zed\AssetStorage\Dependency\Facade\AssetStorageToAssetBridge;
use Spryker\Zed\AssetStorage\Persistence\AssetStorageEntityManager;
use Spryker\Zed\AssetStorage\Persistence\AssetStorageRepository;

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
class AssetStorageCommunicationTester extends Actor
{
    use _generated\AssetStorageCommunicationTesterActions;

    /**
     * @var int
     */
    public const ID_ASSET_DEFAULT = 1;

    /**
     * @var string
     */
    public const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    public const STORE_NAME_EN = 'EN';

    /**
     * @var array<string>
     */
    public const STORE_NAMES_DEFAULT = [
        self::STORE_NAME_DE,
        self::STORE_NAME_EN,
    ];

    /**
     * @var string
     */
    public const ASSET_SLOT_DEFAULT = 'header-test';

    /**
     * @var string
     */
    public const ASSET_UUID_DATA_KEY = 'assetUuid';

    /**
     * @var string
     */
    public const ASSET_CONTENT_DATA_KEY = 'assetContent';

    /**
     * @var string
     */
    public const ASSET_ID_DATA_KEY = 'assetId';

    /**
     * @var string
     */
    protected const ASSETS_DATA_KEY = 'assets';

    /**
     * @var string
     */
    protected const ASSET_SLOT_DATA_KEY = 'assetSlot';

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer|null $assetTransfer
     *
     * @return void
     */
    public function mockAssetFacade(?AssetTransfer $assetTransfer): void
    {
        $assetFacadeMock = Stub::make(
            AssetStorageToAssetBridge::class,
            [
                'findAssetById' => $assetTransfer,
            ],
        );
        $this->mockFactoryMethod('getAssetFacade', $assetFacadeMock);
        $this->mockFactoryMethod('getEntityManager', new AssetStorageEntityManager());
        $this->mockFactoryMethod('getRepository', new AssetStorageRepository());
    }

    /**
     * @param array $expectedStorageData
     * @param string $accessSlot
     *
     * @return void
     */
    public function assertAssetStorage(array $expectedStorageData, string $accessSlot = self::ASSET_SLOT_DEFAULT): void
    {
        $count = (new SpyAssetSlotStorageQuery())
            ->filterByAssetSlot($accessSlot)
            ->count();
        $this->assertSame(count($expectedStorageData), $count, 'qty of storage records is different from expected');

        foreach ($expectedStorageData as $expectedStorageKey => $expectedStorageDataItem) {
            $assetSlotStorage = SpyAssetSlotStorageQuery::create()
                ->findOneByKey($expectedStorageKey);
            $this->assertNotNull($assetSlotStorage, 'no data stored');

            $data = $assetSlotStorage->getData();
            $this->assertSame($expectedStorageDataItem, $data, 'storage data is different from expected');
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return void
     */
    public function haveAssetSlotStorageForAssetTransfer(AssetTransfer $assetTransfer): void
    {
        foreach ($assetTransfer->getStores() as $storeName) {
            $assetSlotStorage = (new SpyAssetSlotStorageQuery())
                ->filterByAssetSlot($assetTransfer->getAssetSlot())
                ->filterByStore($storeName)
                ->findOneOrCreate();

            $data = $assetSlotStorage->getData();
            $data[static::ASSET_SLOT_DATA_KEY] = $assetTransfer->getAssetSlot();
            $data[static::ASSETS_DATA_KEY][] = [
                static::ASSET_ID_DATA_KEY => $assetTransfer->getIdAsset(),
                static::ASSET_UUID_DATA_KEY => $assetTransfer->getAssetUuid(),
                static::ASSET_CONTENT_DATA_KEY => $assetTransfer->getAssetContent(),
            ];

            $assetSlotStorage->setStore($storeName)
                ->setAssetSlot($assetTransfer->getAssetSlot())
                ->setData($data);

            $assetSlotStorage->save();
        }
    }
}
