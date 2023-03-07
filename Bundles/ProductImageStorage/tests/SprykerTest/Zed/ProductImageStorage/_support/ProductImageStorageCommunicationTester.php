<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductImageStorage;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImageQuery;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorage;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductImageStorageCommunicationTester extends Actor
{
    use _generated\ProductImageStorageCommunicationTesterActions;

    /**
     * @var string
     */
    public const PARAM_PROJECT = 'PROJECT';

    /**
     * @var string
     */
    public const PROJECT_SUITE = 'suite';

    /**
     * @return bool
     */
    public function isSuiteProject(): bool
    {
        if (getenv(static::PARAM_PROJECT) === static::PROJECT_SUITE) {
            return true;
        }

        return false;
    }

    /**
     * @param int $sortOrder
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function createProductImageTransferWithSortOrder(int $sortOrder): ProductImageTransfer
    {
        /** @var \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer */
        $productImageTransfer = (new ProductImageBuilder())
            ->seed([ProductImageTransfer::SORT_ORDER => $sortOrder])
            ->build();

        return $productImageTransfer;
    }

    /**
     * @param int $idProductImageSet
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage|null
     */
    public function findProductImageSetToProductImage(int $idProductImageSet): ?SpyProductImageSetToProductImage
    {
        return SpyProductImageSetToProductImageQuery::create()
            ->findOneByFkProductImageSet($idProductImageSet);
    }

    /**
     * @param int $idProductImageSet
     *
     * @return void
     */
    public function deleteProductImageSetToProductImage(int $idProductImageSet): void
    {
        $productImageSetToProductImageEntity = $this->findProductImageSetToProductImage($idProductImageSet);
        if ($productImageSetToProductImageEntity === null) {
            return;
        }

        $productImageSetToProductImageEntity->delete();
    }

    /**
     * @param int $idProductConcrete
     * @param string $locale
     *
     * @return \Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorage
     */
    public function createProductConcreteImageStorage(int $idProductConcrete, string $locale): SpyProductConcreteImageStorage
    {
        $productConcreteImageStorageEntity = (new SpyProductConcreteImageStorage())
            ->setFkProduct($idProductConcrete)
            ->setKey(uniqid())
            ->setData([])
            ->setLocale($locale);

        $productConcreteImageStorageEntity->save();

        return $productConcreteImageStorageEntity;
    }
}
