<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSetPageSearch;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\LocalizedProductSetBuilder;
use Generated\Shared\DataBuilder\ProductSetBuilder;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductSetTransfer;
use Orm\Zed\ProductSetPageSearch\Persistence\SpyProductSetPageSearchQuery;
use Spryker\Zed\ProductSet\Dependency\ProductSetEvents;
use Spryker\Zed\ProductSetPageSearch\Business\ProductSetPageSearchFacade;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Event\Listener\ProductSetPageSearchPublishListener;

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
class ProductSetPageSearchCommunicationTester extends Actor
{
    use _generated\ProductSetPageSearchCommunicationTesterActions;

    /**
     * @var string
     */
    public const PARAM_PROJECT = 'PROJECT';

    /**
     * @var string
     */
    public const PROJECT_SUITE = 'suite';

    /**
     * @var string
     */
    protected const KEY_SEARCH_RESULT_DATA = 'search-result-data';

    /**
     * @var string
     */
    protected const KEY_IMAGE_SETS = 'image_sets';

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
     * @param array<\Generated\Shared\Transfer\ProductSetTransfer> $productSetTransfers
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

    /**
     * @param array<\Generated\Shared\Transfer\ProductImageTransfer> $productImageTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    public function createProductSetWithProductImages(array $productImageTransfers, LocaleTransfer $localeTransfer): ProductSetTransfer
    {
        $localizedProductSetTransfer = (new LocalizedProductSetBuilder())->withProductSetData()->build();
        $localizedProductSetTransfer->setLocale($localeTransfer);

        $productAbstractTransfer = $this->haveProductAbstract();

        $productSetTransfer = (new ProductSetBuilder())->withImageSet()->build();
        $productSetTransfer->addLocalizedData($localizedProductSetTransfer);
        $productSetTransfer->setIdProductAbstracts([$productAbstractTransfer->getIdProductAbstract()]);
        $productSetTransfer->getImageSets()[0]->setProductImages(new ArrayObject($productImageTransfers));

        return $this->getLocator()->productSet()->facade()->createProductSet($productSetTransfer);
    }

    /**
     * @param int $idProductSet
     *
     * @return array
     */
    public function getProductSetImages(int $idProductSet): array
    {
        $productSetStorage = SpyProductSetPageSearchQuery::create()->findOneByFkProductSet($idProductSet);

        return current($productSetStorage->getData()[static::KEY_SEARCH_RESULT_DATA][static::KEY_IMAGE_SETS]);
    }
}
