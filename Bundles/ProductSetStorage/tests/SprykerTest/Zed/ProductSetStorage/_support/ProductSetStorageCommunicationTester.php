<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSetStorage;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\LocalizedProductSetBuilder;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\DataBuilder\ProductSetBuilder;
use Generated\Shared\Transfer\ProductImageTransfer;
use Generated\Shared\Transfer\ProductSetTransfer;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductSetStorageCommunicationTester extends Actor
{
    use _generated\ProductSetStorageCommunicationTesterActions;

   /**
    * Define custom actions here
    */

    public const PARAM_PROJECT = 'PROJECT';

    public const PROJECT_SUITE = 'suite';

    /**
     * @return bool
     */
    public function isSuiteProject()
    {
        if (getenv(static::PARAM_PROJECT) === static::PROJECT_SUITE) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer[] $productImageTransfers
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function createProductSetWithProductImages(array $productImageTransfers): ProductSetTransfer
    {
        $localizedProductSetTransfer = (new LocalizedProductSetBuilder())->withProductSetData()->build();
        $localizedProductSetTransfer->setLocale($this->haveLocale());

        $productAbstractTransfer = $this->haveProductAbstract();

        $productSetTransfer = (new ProductSetBuilder())->withImageSet()->build();
        $productSetTransfer->addLocalizedData($localizedProductSetTransfer);
        $productSetTransfer->setIdProductAbstracts([$productAbstractTransfer->getIdProductAbstract()]);
        $productSetTransfer->getImageSets()[0]->setProductImages(new ArrayObject($productImageTransfers));

        return $this->getProductSetFacade()->createProductSet($productSetTransfer);
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
     * @return \Spryker\Zed\ProductSet\Business\ProductSetFacadeInterface
     */
    protected function getProductSetFacade()
    {
        return $this->getLocator()->productSet()->facade();
    }
}
