<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ProductConfiguration\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface;
use Spryker\Client\Price\PriceDependencyProvider;
use Spryker\Client\ProductConfiguration\Dependency\Client\ProductConfigurationToCurrencyClientInterface;
use Spryker\Client\ProductConfiguration\ProductConfigurationDependencyProvider;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderPluginInterface;
use Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface;
use Spryker\Client\StoreStorage\Plugin\Store\StoreStorageStoreExpanderPlugin;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ProductConfiguration
 * @group Client
 * @group ExpandProductConfiguratorRequestWithContextDataTest
 * Add your own group annotations below this line
 */
class ExpandProductConfiguratorRequestWithContextDataTest extends Unit
{
    /**
     * @var string
     */
    protected const CURRENCY_CODE = 'EUR';

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var \SprykerTest\Client\ProductConfiguration\ProductConfigurationClientTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->addDependencies();
    }

    /**
     * @return void
     */
    public function testThrowsExceptionWhenProductConfiguratorRequestDataIsMissing(): void
    {
        // Arrange
        $productConfiguratorRequestTransfer = new ProductConfiguratorRequestTransfer();

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getClient()->expandProductConfiguratorRequestWithContextData($productConfiguratorRequestTransfer);
    }

    /**
     * @return void
     */
    public function testExpandsWithContextData(): void
    {
        // Arrange
        $productConfiguratorRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(new ProductConfiguratorRequestDataTransfer());

        // Act
        $productConfiguratorRequestTransfer = $this->tester->getClient()->expandProductConfiguratorRequestWithContextData($productConfiguratorRequestTransfer);
        $productConfiguratorRequestDataTransfer = $productConfiguratorRequestTransfer->getProductConfiguratorRequestData();

        // Assert
        $this->assertSame(
            $productConfiguratorRequestDataTransfer->getStoreName(),
            $this->tester->getLocator()->store()->client()->getCurrentStore()->getName(),
            'Expects store name to be equal to current one.',
        );

        $this->assertSame(
            $productConfiguratorRequestDataTransfer->getCurrencyCode(),
            $this->isDynamicStoreEnabled() ? static::CURRENCY_CODE : $this->tester->getLocator()->currency()->client()->getCurrent()->getCode(),
            'Expects currency code to be equal to current one.',
        );

        $this->assertSame(
            $productConfiguratorRequestDataTransfer->getPriceMode(),
            $this->tester->getLocator()->price()->client()->getCurrentPriceMode(),
            'Expects price mode to be equal to current one.',
        );

        $this->assertSame(
            $productConfiguratorRequestDataTransfer->getLocaleName(),
            $this->tester->getLocator()->locale()->client()->getCurrentLocale(),
            'Expects locale name to be equal to current one.',
        );
    }

    /**
     * @return void
     */
    public function testExecutesProductConfiguratorRequestExpanderPlugins(): void
    {
        // Arrange
        $productConfiguratorRequestTransfer = (new ProductConfiguratorRequestTransfer())
            ->setProductConfiguratorRequestData(new ProductConfiguratorRequestDataTransfer());

        $productConfiguratorRequestExpanderPluginMock = $this->getMockBuilder(
            ProductConfiguratorRequestExpanderPluginInterface::class,
        )->getMock();

        // Assert
        $productConfiguratorRequestExpanderPluginMock->expects($this->once())->method('expand');

        // Act
        $this->tester->setDependency(
            ProductConfigurationDependencyProvider::PLUGINS_PRODUCT_CONFIGURATOR_REQUEST_EXPANDER,
            [$productConfiguratorRequestExpanderPluginMock],
        );

        $this->tester->getClient()->expandProductConfiguratorRequestWithContextData($productConfiguratorRequestTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerTest\Client\ProductConfiguration\Client\Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface
     */
    protected function createStoreStorageStoreExpanderPluginMock(): StoreExpanderPluginInterface
    {
        $storeStorageStoreExpanderPluginMock = $this->createMock(StoreStorageStoreExpanderPlugin::class);
        $storeStorageStoreExpanderPluginMock->method('expand')
            ->willReturn((new StoreTransfer())
                ->setName(static::DEFAULT_STORE)
                ->setDefaultCurrencyIsoCode(static::CURRENCY_CODE));

        return $storeStorageStoreExpanderPluginMock;
    }

    /**
     * @return void
     */
    protected function addDependencies(): void
    {
        $currencyClientMock = $this->createMock(ProductConfigurationToCurrencyClientInterface::class);
        $currencyClientMock->method('getCurrent')
            ->willReturn((new CurrencyTransfer())
                ->setCode(static::CURRENCY_CODE));
        $this->tester->setDependency(ProductConfigurationDependencyProvider::CLIENT_CURRENCY, $currencyClientMock);

        $quoteClientMock = $this->createMock(PriceToQuoteClientInterface::class);
        $quoteClientMock->method('getQuote')
            ->willReturn((new QuoteTransfer()));
        $this->tester->setDependency(PriceDependencyProvider::CLIENT_QUOTE, $quoteClientMock);
    }

    /**
     * @return bool
     */
    protected function isDynamicStoreEnabled(): bool
    {
        return (bool)getenv('SPRYKER_DYNAMIC_STORE_MODE');
    }
}
