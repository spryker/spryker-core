<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector;

use ArrayObject;
use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceCartConnector\Business\Builder\ItemIdentifierBuilder;
use Spryker\Zed\PriceCartConnector\Business\Builder\ItemIdentifierBuilderInterface;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorBusinessFactory;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacade;
use Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToUtilEncodingServiceBridge;
use Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToUtilEncodingServiceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToUtilTextServiceBridge;
use Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToUtilTextServiceInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorDependencyProvider;
use SprykerTest\Zed\PriceCartConnector\Business\Fixture\PriceProductFacadeStub;

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
 * @method \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class PriceCartConnectorBusinessTester extends Actor
{
    use _generated\PriceCartConnectorBusinessTesterActions;

    /**
     * @var string
     */
    protected const STORE_DE = 'DE';

    /**
     * @uses \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var string
     */
    protected const TEST_SKU_1 = 'TEST_SKU_1';

    /**
     * @var string
     */
    protected const TEST_CURRENCY_1 = 'TCF';

    /**
     * @var string
     */
    protected const TEST_STORE_NAME = 'DE';

    /**
     * @var int
     */
    protected const TEST_ITEM_ID = 123;

    /**
     * @param array $itemsData
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteWithItems(array $itemsData, CurrencyTransfer $currencyTransfer): QuoteTransfer
    {
        $storeTransfer = $this->haveStore([StoreTransfer::NAME => static::STORE_DE]);
        $itemsTransfers = $this->createItemTransfersBySkuAndPriceCollection($itemsData, $currencyTransfer, $storeTransfer);

        return (new QuoteBuilder([
            QuoteTransfer::STORE => $storeTransfer,
            QuoteTransfer::CURRENCY => $currencyTransfer,
            QuoteTransfer::PRICE_MODE => static::PRICE_MODE_GROSS,
        ]))->build()->setItems(new ArrayObject($itemsTransfers));
    }

    /**
     * @param array $itemsData
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function createItemTransfersBySkuAndPriceCollection(
        array $itemsData,
        CurrencyTransfer $currencyTransfer,
        StoreTransfer $storeTransfer
    ): array {
        $itemsTransfers = [];
        foreach ($itemsData as $sku => $itemPrice) {
            $productConcreteTransfer = $this->haveProduct([
                ProductConcreteTransfer::SKU => $sku,
                ProductConcreteTransfer::ABSTRACT_SKU => $sku,
            ]);

            if ($itemPrice !== null) {
                $this->havePriceProduct([
                    PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
                    PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
                    PriceProductTransfer::MONEY_VALUE => [
                        MoneyValueTransfer::NET_AMOUNT => $itemPrice,
                        MoneyValueTransfer::GROSS_AMOUNT => $itemPrice,
                        MoneyValueTransfer::STORE => $storeTransfer->getName(),
                        MoneyValueTransfer::CURRENCY => $currencyTransfer,
                    ],
                ]);
            }

            $itemsTransfers[] = (new ItemTransfer())->fromArray($productConcreteTransfer->toArray(), true);
        }

        return $itemsTransfers;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig $priceCartConnectorConfigMock
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacadeStub
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacadeMock
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface $currencyFacadeMock
     *
     * @return \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface
     */
    public function createAndConfigurePriceCartConnectorFacade(
        PriceCartConnectorConfig $priceCartConnectorConfigMock,
        PriceCartToPriceProductInterface $priceProductFacadeStub,
        PriceCartToPriceInterface $priceFacadeMock,
        PriceCartConnectorToCurrencyFacadeInterface $currencyFacadeMock
    ): PriceCartConnectorFacadeInterface {
        $priceCartConnectorBusinessFactory = $this->createAndConfigurePriceCartConnectorBusinessFactory(
            $priceCartConnectorConfigMock,
            $priceProductFacadeStub,
            $priceFacadeMock,
            $currencyFacadeMock,
        );

        $priceCartConnectorFacade = $this->createPriceCartConnectorFacade();
        $priceCartConnectorFacade->setFactory($priceCartConnectorBusinessFactory);

        return $priceCartConnectorFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeTransferWithItem(): CartChangeTransfer
    {
        $currencyTransfer = (new CurrencyTransfer())->setCode(static::TEST_CURRENCY_1);
        $storeTransfer = (new StoreTransfer())->setName(static::TEST_STORE_NAME);

        $quoteTransfer = (new QuoteTransfer())
            ->setCurrency($currencyTransfer)
            ->setStore($storeTransfer)
            ->setPriceMode(static::PRICE_MODE_GROSS);

        $itemTransfer = (new ItemTransfer())
            ->setSku(static::TEST_SKU_1)
            ->setId(static::TEST_ITEM_ID);

        return (new CartChangeTransfer())
            ->setQuote($quoteTransfer)
            ->addItem($itemTransfer);
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface
     */
    public function createPriceProductFacadeStub(): PriceCartToPriceProductInterface
    {
        return new PriceProductFacadeStub();
    }

    /**
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return array<string>
     */
    public function getCartPreCheckResponseTransferMessages(CartPreCheckResponseTransfer $cartPreCheckResponseTransfer): array
    {
        return array_map(function (MessageTransfer $messageTransfer) {
            return $messageTransfer->getValue();
        }, $cartPreCheckResponseTransfer->getMessages()->getArrayCopy());
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToUtilEncodingServiceInterface
     */
    public function createPriceCartConnectorToUtilEncodingServiceBridge(): PriceCartConnectorToUtilEncodingServiceInterface
    {
        return new PriceCartConnectorToUtilEncodingServiceBridge(
            $this->getLocator()->utilEncoding()->service(),
        );
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Dependency\Service\PriceCartConnectorToUtilTextServiceInterface
     */
    public function createPriceCartConnectorToUtilTextServiceBridge(): PriceCartConnectorToUtilTextServiceInterface
    {
        return new PriceCartConnectorToUtilTextServiceBridge(
            $this->getLocator()->utilText()->service(),
        );
    }

    /**
     * @param \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig $priceCartConnectorConfig
     *
     * @return \Spryker\Zed\PriceCartConnector\Business\Builder\ItemIdentifierBuilderInterface
     */
    public function createItemIdentifierBuilder(PriceCartConnectorConfig $priceCartConnectorConfig): ItemIdentifierBuilderInterface
    {
        return new ItemIdentifierBuilder(
            $priceCartConnectorConfig,
            $this->createPriceCartConnectorToUtilEncodingServiceBridge(),
            $this->createPriceCartConnectorToUtilTextServiceBridge(),
        );
    }

    /**
     * @param list<string> $itemFieldsForIdentifier
     * @param bool $isZeroPriceEnabledForCartActions
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig
     */
    public function createPriceCartConnectorConfigMock(
        array $itemFieldsForIdentifier = [],
        bool $isZeroPriceEnabledForCartActions = false
    ): PriceCartConnectorConfig {
        return Stub::make(PriceCartConnectorConfig::class, [
            'getItemFieldsForIdentifier' => $itemFieldsForIdentifier,
            'isZeroPriceEnabledForCartActions' => $isZeroPriceEnabledForCartActions,
            'getItemFieldsForIsSameItemComparison' => [ItemTransfer::SKU],
        ]);
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function createContainer(): Container
    {
        return new Container();
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacadeStub
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacadeMock
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface $currencyFacadeMock
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function configureContainer(
        Container $container,
        PriceCartToPriceProductInterface $priceProductFacadeStub,
        PriceCartToPriceInterface $priceFacadeMock,
        PriceCartConnectorToCurrencyFacadeInterface $currencyFacadeMock
    ): Container {
        $container->set(PriceCartConnectorDependencyProvider::FACADE_PRICE_PRODUCT, function (Container $container) use ($priceProductFacadeStub) {
            return $priceProductFacadeStub;
        });

        $container->set(PriceCartConnectorDependencyProvider::FACADE_PRICE, function (Container $container) use ($priceFacadeMock) {
            return $priceFacadeMock;
        });

        $container->set(PriceCartConnectorDependencyProvider::FACADE_CURRENCY, function (Container $container) use ($currencyFacadeMock) {
            return $currencyFacadeMock;
        });

        $container->set(PriceCartConnectorDependencyProvider::PLUGINS_CART_ITEM_QUANTITY_COUNTER_STRATEGY, function (Container $container) {
            return [];
        });

        $container->set(PriceCartConnectorDependencyProvider::SERVICE_UTIL_TEXT, function (Container $container) {
            return $this->createPriceCartConnectorToUtilTextServiceBridge();
        });

        $container->set(PriceCartConnectorDependencyProvider::SERVICE_UTIL_ENCODING, function (Container $container) {
            return $this->createPriceCartConnectorToUtilEncodingServiceBridge();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorBusinessFactory
     */
    protected function createPriceCartConnectorBusinessFactory(): PriceCartConnectorBusinessFactory
    {
        return new PriceCartConnectorBusinessFactory();
    }

    /**
     * @return \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorFacadeInterface
     */
    protected function createPriceCartConnectorFacade(): PriceCartConnectorFacadeInterface
    {
        return new PriceCartConnectorFacade();
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig $priceCartConnectorConfigMock
     * @param bool $isZeroPriceEnabledForCartActions
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig
     */
    protected function configurePriceCartConnectorConfigMock(
        PriceCartConnectorConfig $priceCartConnectorConfigMock,
        bool $isZeroPriceEnabledForCartActions
    ): PriceCartConnectorConfig {
        $priceCartConnectorConfigMock
            ->method('isZeroPriceEnabledForCartActions')
            ->willReturn($isZeroPriceEnabledForCartActions);

        $priceCartConnectorConfigMock
            ->method('getItemFieldsForIsSameItemComparison')
            ->willReturn(['sku']);

        return $priceCartConnectorConfigMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig $priceCartConnectorConfigMock
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacadeStub
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacadeMock
     * @param \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface $currencyFacadeMock
     *
     * @return \Spryker\Zed\PriceCartConnector\Business\PriceCartConnectorBusinessFactory
     */
    protected function createAndConfigurePriceCartConnectorBusinessFactory(
        PriceCartConnectorConfig $priceCartConnectorConfigMock,
        PriceCartToPriceProductInterface $priceProductFacadeStub,
        PriceCartToPriceInterface $priceFacadeMock,
        PriceCartConnectorToCurrencyFacadeInterface $currencyFacadeMock
    ): PriceCartConnectorBusinessFactory {
        $container = $this->createContainer();
        $container = $this->configureContainer($container, $priceProductFacadeStub, $priceFacadeMock, $currencyFacadeMock);

        $priceCartConnectorBusinessFactory = $this->createPriceCartConnectorBusinessFactory();
        $priceCartConnectorBusinessFactory->setConfig($priceCartConnectorConfigMock);
        $priceCartConnectorBusinessFactory->setContainer($container);

        return $priceCartConnectorBusinessFactory;
    }
}
