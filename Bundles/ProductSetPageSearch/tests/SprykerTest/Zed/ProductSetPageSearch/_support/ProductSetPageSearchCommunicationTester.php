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
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function generateProductSetTransfer(): ProductSetTransfer
    {
        $localizedProductSetTransfer = (new LocalizedProductSetBuilder())
            ->withProductSetData()
            ->build()
            ->setLocale($this->haveLocale());

        $productSetTransfer = (new ProductSetBuilder())
            ->build()
            ->addLocalizedData($localizedProductSetTransfer)
            ->setIdProductAbstracts([
                $this->haveProductAbstract()->getIdProductAbstract(),
                $this->haveProductAbstract()->getIdProductAbstract(),
            ]);

        return $this->getProductSetFacade()->createProductSet($productSetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    public function deleteProductSet(ProductSetTransfer $productSetTransfer): void
    {
        $this->getProductSetFacade()->deleteProductSet($productSetTransfer);
    }

    /**
     * @return \Spryker\Zed\ProductSet\Business\ProductSetFacadeInterface
     */
    protected function getProductSetFacade(): ProductSetFacadeInterface
    {
        return $this->getLocator()->productSet()->facade();
    }
}
