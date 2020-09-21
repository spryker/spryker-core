<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductConfiguration\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Spryker\Client\ProductConfiguration\ProductConfigurationFactory;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfiguration
 * @group ProductConfigurationClientTest
 * Add your own group annotations below this line
 */
class ProductConfigurationClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\ProductConfiguration\ProductConfigurationClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testPrepareProductConfiguratorRedirect(): void
    {
        /**
         * @var \Spryker\Client\ProductConfiguration\ProductConfigurationClient $productConfigurationClient
         */
        $productConfigurationClient = $this->tester->getClient();
        $configurationFactoryMock = $this->createFactoryMock();

        $configurationFactoryMock->method('getProductConfiguratorRequestPlugins')->willReturn(null);
        $configurationFactoryMock->method('getDefaultProductConfiguratorRequestPlugin')->willReturn(null);

        $productConfigurationClient->setFactory($configurationFactoryMock);

        $productConfigurationClient->prepareProductConfiguratorRedirect(new ProductConfiguratorRequestTransfer());
    }

    protected function createFactoryMock()
    {
        return $this->getMockBuilder(ProductConfigurationFactory::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept([
                'createProductConfiguratorRedirectResolver',
            ])
            ->onlyMethods([
                'getProductConfiguratorRequestPlugins',
                'getDefaultProductConfiguratorRequestPlugin'
            ])
            ->getMock();
    }
}
