<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOffer;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOffer\Business\ProductOfferFacade;
use Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepository;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;
use Spryker\Zed\ProductOffer\ProductOfferDependencyProvider;

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
 * @method void pause()
 * @method \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductOfferBusinessTester extends Actor
{
    use _generated\ProductOfferBusinessTesterActions;

    /**
     * @return void
     */
    public function truncateProductOffers(): void
    {
        $this->truncateTableRelations($this->getProductOfferPropelQuery());
    }

    /**
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    public function getProductOfferPropelQuery(): SpyProductOfferQuery
    {
        return SpyProductOfferQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface
     */
    public function getProductOfferRepository(): ProductOfferRepositoryInterface
    {
        return new ProductOfferRepository();
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface
     */
    public function createProductOfferFacadeWithMockedStoreFacade(): ProductOfferFacadeInterface
    {
        $storeFacadeMock = Stub::makeEmpty(ProductOfferToStoreFacadeInterface::class);
        $storeFacadeMock->method('getStoreByName')
            ->willReturnCallback(function (string $storeName) {
                return (new StoreTransfer())->setName($storeName);
            });

        $this->mockFactoryMethod('getStoreFacade', $storeFacadeMock);
        $factory = $this->mockFactoryMethod('getRepository', $this->getProductOfferRepository());
        $factory->setConfig($this->getModuleConfig());

        $container = new Container();
        $container->set(ProductOfferDependencyProvider::PLUGINS_PRODUCT_OFFER_EXPANDER, []);
        $factory->setContainer($container);

        return (new ProductOfferFacade())->setFactory($factory);
    }
}
