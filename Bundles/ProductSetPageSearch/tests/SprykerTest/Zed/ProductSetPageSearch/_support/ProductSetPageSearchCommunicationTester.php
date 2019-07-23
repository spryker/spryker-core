<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSetPageSearch;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\LocalizedProductSetBuilder;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductSetTransfer;
use Spryker\Zed\ProductSet\Dependency\ProductSetEvents;
use Spryker\Zed\ProductSetPageSearch\Business\ProductSetPageSearchFacade;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageSearchPublishListener;

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

        return $this->haveProductSet([
            ProductSetTransfer::LOCALIZED_DATA => new ArrayObject([$localizedProductSetTransfer]),
            ProductSetTransfer::ID_PRODUCT_ABSTRACTS => [
                $this->haveProductAbstract()->getIdProductAbstract(),
                $this->haveProductAbstract()->getIdProductAbstract(),
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer[] $productSetTransfers
     * @param \Spryker\Zed\ProductSetPageSearch\Business\ProductSetPageSearchFacade $productSetPageSearchFacade
     *
     * @return void
     */
    public function publishProductSetTransfers(array $productSetTransfers, ProductSetPageSearchFacade $productSetPageSearchFacade): void
    {
        if ($productSetTransfers === []) {
            return;
        }

        $eventTransfers = [];
        foreach ($productSetTransfers as $productSetTransfer) {
            $eventTransfers[] = (new EventEntityTransfer())->setId($productSetTransfer->getIdProductSet());
        }

        (new ProductSetPageSearchPublishListener())
            ->setFacade($productSetPageSearchFacade)
            ->handleBulk($eventTransfers, ProductSetEvents::PRODUCT_SET_PUBLISH);
    }
}
