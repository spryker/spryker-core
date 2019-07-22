<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSetPageSearch;

use Codeception\Actor;
use Generated\Shared\DataBuilder\LocalizedProductSetBuilder;
use Generated\Shared\DataBuilder\ProductSetBuilder;
use Generated\Shared\Transfer\ProductSetTransfer;
use Orm\Zed\ProductSetPageSearch\Persistence\SpyProductSetPageSearchQuery;
use Spryker\Zed\ProductSet\Business\ProductSetFacadeInterface;

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
class ProductSetPageSearchCommunicationTester extends Actor
{
    use _generated\ProductSetPageSearchCommunicationTesterActions;

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
     * @param int $productSetAmount
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer[]
     */
    public function createProductSets(int $productSetAmount): array
    {
        $productSetTransfers = [];
        for ($i = 0; $i < $productSetAmount; $i++) {
            $productSetTransfers[] = $this->createProductSetTransfer();
        }

        return $productSetTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function createProductSetTransfer(): ProductSetTransfer
    {
        $localizedProductSetTransfer = (new LocalizedProductSetBuilder())->withProductSetData()->build();
        $localizedProductSetTransfer->setLocale($this->haveLocale());

        $productAbstractTransfer = $this->haveProductAbstract();

        $productSetTransfer = (new ProductSetBuilder())->withImageSet()->build();
        $productSetTransfer->addLocalizedData($localizedProductSetTransfer);
        $productSetTransfer->setIdProductAbstracts([$productAbstractTransfer->getIdProductAbstract()]);

        return $this->getProductSetFacade()->createProductSet($productSetTransfer);
    }

    /**
     * @param int $fkProductSet
     *
     * @return void
     */
    public function deleteProductSetSearchByFkProductSet(int $fkProductSet): void
    {
        SpyProductSetPageSearchQuery::create()->filterByFkProductSet($fkProductSet)->delete();
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\ProductSetFacadeInterface
     */
    protected function getProductSetFacade(): ProductSetFacadeInterface
    {
        return $this->getLocator()->productSet()->facade();
    }
}
