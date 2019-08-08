<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductBundleProductListConnector;

use Codeception\Actor;
use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductBundleProductListConnector\Business\ProductBundleProductListConnectorBusinessFactory;
use Spryker\Zed\ProductBundleProductListConnector\Business\ProductBundleProductListConnectorFacadeInterface;
use Spryker\Zed\ProductBundleProductListConnector\ProductBundleProductListConnectorDependencyProvider;

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
class ProductBundleProductListConnectorBusinessTester extends Actor
{
    use _generated\ProductBundleProductListConnectorBusinessTesterActions;

    /**
     * @param int[] $productIds
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function createProductListTransfer(array $productIds, string $type): ProductListTransfer
    {
        $productListProductConcreteRelationTransfer = new ProductListProductConcreteRelationTransfer();
        $productListProductConcreteRelationTransfer->setProductIds($productIds);

        $productListTransfer = new ProductListTransfer();
        $productListTransfer->setProductListProductConcreteRelation($productListProductConcreteRelationTransfer);
        $productListTransfer->setType($type);

        return $productListTransfer;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $productBundleProductListConnectorToProductBundleFacadeBridgeMock
     *
     * @return \Spryker\Zed\ProductBundleProductListConnector\Business\ProductBundleProductListConnectorFacadeInterface
     */
    public function getFacadeWitMockDependency(
        MockObject $productBundleProductListConnectorToProductBundleFacadeBridgeMock
    ): ProductBundleProductListConnectorFacadeInterface {
        /** @var \Spryker\Zed\ProductBundleProductListConnector\Business\ProductBundleProductListConnectorFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade $productBundleProductListConnectorFacade */
        $productBundleProductListConnectorFacade = $this->getFacade();
        $productBundleProductListConnectorBusinessFactory = $this->getProductBundleProductListConnectorBusinessFactory($productBundleProductListConnectorToProductBundleFacadeBridgeMock);
        $productBundleProductListConnectorFacade->setFactory($productBundleProductListConnectorBusinessFactory);

        return $productBundleProductListConnectorFacade;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $productBundleProductListConnectorToProductBundleFacadeBridgeMock
     *
     * @return \Spryker\Zed\ProductBundleProductListConnector\Business\ProductBundleProductListConnectorBusinessFactory
     */
    protected function getProductBundleProductListConnectorBusinessFactory(
        MockObject $productBundleProductListConnectorToProductBundleFacadeBridgeMock
    ): ProductBundleProductListConnectorBusinessFactory {
        $container = new Container();
        $dependencyProvider = new ProductBundleProductListConnectorDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $container[ProductBundleProductListConnectorDependencyProvider::FACADE_PRODUCT_BUNDLE] = function (Container $container) use ($productBundleProductListConnectorToProductBundleFacadeBridgeMock) {
            return $productBundleProductListConnectorToProductBundleFacadeBridgeMock;
        };

        $productBundleProductListConnectorBusinessFactory = new ProductBundleProductListConnectorBusinessFactory();
        $productBundleProductListConnectorBusinessFactory->setContainer($container);

        return $productBundleProductListConnectorBusinessFactory;
    }
}
