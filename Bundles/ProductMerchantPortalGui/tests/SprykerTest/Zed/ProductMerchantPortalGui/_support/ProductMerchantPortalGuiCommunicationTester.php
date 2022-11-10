<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductMerchantPortalGui;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\PriceProductBuilder;
use Generated\Shared\DataBuilder\PriceProductDimensionBuilder;
use Generated\Shared\DataBuilder\PriceTypeBuilder;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MerchantUserTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceTypeTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductConcreteEditFormDataProvider;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductConcreteEditFormDataProviderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMerger;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMergerInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapper;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReader;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReaderInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceBridge;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;
use Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiDependencyProvider;
use Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductMapperPluginInterface;

/**
 * Inherited Methods
 *
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
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductMerchantPortalGuiCommunicationTester extends Actor
{
    use _generated\ProductMerchantPortalGuiCommunicationTesterActions;

    /**
     * @var int
     * Fake IDs here and below are used to not depend on the project data.
     */
    public const FAKE_CURRENCY_ID_1 = - 1;

    /**
     * @var int
     */
    public const FAKE_CURRENCY_ID_2 = - 2;

    /**
     * @var string
     */
    public const FAKE_PRICE_TYPE_1 = 'PRICE_TYPE_1';

    /**
     * @var string
     */
    public const FAKE_PRICE_TYPE_2 = 'PRICE_TYPE_2';

    /**
     * @var string
     */
    public const FAKE_PRICE_DIMENSION = 'FAKE_PRICE_DIMENSION';

    /**
     * @var int
     */
    public const FAKE_STORE_ID_1 = - 1;

    /**
     * @var int
     */
    public const FAKE_STORE_ID_2 = - 2;

    /**
     * @var string
     */
    protected const CURRENCY_CODE_EUR = 'EUR';

    /**
     * @param array<\Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductMapperPluginInterface> $priceProductMapperPlugins
     *
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\PriceProductMapperInterface
     */
    public function createPriceProductMapper(array $priceProductMapperPlugins = []): PriceProductMapperInterface
    {
        return new PriceProductMapper(
            $this->createProductMerchantPortalGuiToPriceProductFacadeBridge(),
            $this->createProductMerchantPortalGuiToCurrencyFacadeBridge(),
            $this->createProductMerchantPortalGuiToMoneyFacadeBridge(),
            $this->createPriceProductMerger(),
            $this->createProductMerchantPortalGuiToUtilEncodingServiceBridge(),
            $priceProductMapperPlugins,
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected function createProductMerchantPortalGuiToPriceProductFacadeBridge(): ProductMerchantPortalGuiToPriceProductFacadeInterface
    {
        return new ProductMerchantPortalGuiToPriceProductFacadeBridge(
            $this->getLocator()->priceProduct()->facade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface
     */
    protected function createProductMerchantPortalGuiToCurrencyFacadeBridge(): ProductMerchantPortalGuiToCurrencyFacadeInterface
    {
        return new ProductMerchantPortalGuiToCurrencyFacadeBridge(
            $this->getLocator()->currency()->facade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface
     */
    protected function createProductMerchantPortalGuiToMoneyFacadeBridge(): ProductMerchantPortalGuiToMoneyFacadeInterface
    {
        return new ProductMerchantPortalGuiToMoneyFacadeBridge(
            $this->getLocator()->money()->facade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected function createProductMerchantPortalGuiToUtilEncodingServiceBridge(): ProductMerchantPortalGuiToUtilEncodingServiceInterface
    {
        return new ProductMerchantPortalGuiToUtilEncodingServiceBridge(
            $this->getLocator()->utilEncoding()->service(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\Merger\PriceProductMergerInterface
     */
    protected function createPriceProductMerger(): PriceProductMergerInterface
    {
        return new PriceProductMerger([]);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductMapperPluginInterface
     */
    public function createSetIdProductAbstractPriceProductMapperPluginMock(): PriceProductMapperPluginInterface
    {
        return Stub::makeEmpty(
            PriceProductMapperPluginInterface::class,
            [
                'mapRequestDataToPriceProductCriteriaTransfer' =>
                    function (
                        array $data,
                        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
                    ) {
                        return $priceProductCriteriaTransfer->setIdProductAbstract($data['idProductAbstract']);
                    },
            ],
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeBridge
     */
    public function createPriceProductFacadeMock(): ProductMerchantPortalGuiToPriceProductFacadeBridge
    {
        $fakePriceTypes = $this->getFakePriceTypes();

        return Stub::make(
            ProductMerchantPortalGuiToPriceProductFacadeBridge::class,
            [
                'getPriceTypeValues' => function () use ($fakePriceTypes) {
                    return $fakePriceTypes;
                },
            ],
        );
    }

    /**
     * @param array<string, callable> $mockedMethods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    public function createProductMerchantPortalGuiToPriceProductFacadeMock(
        array $mockedMethods
    ): ProductMerchantPortalGuiToPriceProductFacadeInterface {
        return Stub::make(ProductMerchantPortalGuiToPriceProductFacadeBridge::class, $mockedMethods);
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeBridge
     */
    public function createCurrencyFacadeMock(): ProductMerchantPortalGuiToCurrencyFacadeBridge
    {
        $currencies = $this->getFakeCurrencies();

        return Stub::make(
            ProductMerchantPortalGuiToCurrencyFacadeBridge::class,
            [
                'getByIdCurrency' => function (int $idCurrency) use ($currencies) {
                    if (array_key_exists($idCurrency, $currencies)) {
                        return $currencies[$idCurrency];
                    }

                    return (new CurrencyTransfer());
                },
            ],
        );
    }

    /**
     * @param array<string, callable> $mockedMethods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    public function createMerchantUserFacadeMock(array $mockedMethods = []): ProductMerchantPortalGuiToMerchantUserFacadeInterface
    {
        return Stub::make(
            ProductMerchantPortalGuiToMerchantUserFacadeBridge::class,
            $mockedMethods,
        );
    }

    /**
     * @param array $mockedMethods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface
     */
    public function createMerchantProductFacadeMock(array $mockedMethods = []): ProductMerchantPortalGuiToMerchantProductFacadeInterface
    {
        return Stub::make(
            ProductMerchantPortalGuiToMerchantProductFacadeBridge::class,
            $mockedMethods,
        );
    }

    /**
     * @param array $mockedMethods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    public function createProductFacadeMock(array $mockedMethods = []): ProductMerchantPortalGuiToProductFacadeInterface
    {
        return Stub::make(
            ProductMerchantPortalGuiToProductFacadeBridge::class,
            $mockedMethods,
        );
    }

    /**
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory
     */
    public function createProductMerchantPortalGuiCommunicationFactoryMock(): ProductMerchantPortalGuiCommunicationFactory
    {
        $currencyFacade = $this->createCurrencyFacadeMock();
        $priceProductFacade = $this->createPriceProductFacadeMock();

        return Stub::make(
            ProductMerchantPortalGuiCommunicationFactory::class,
            [
                'getCurrencyFacade' => function () use ($currencyFacade) {
                    return $currencyFacade;
                },
                'getPriceProductFacade' => function () use ($priceProductFacade) {
                    return $priceProductFacade;
                },
                'resolveDependencyProvider' => function () {
                    return (new ProductMerchantPortalGuiDependencyProvider());
                },
            ],
        );
    }

    /**
     * @param int $priceValue
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function createFakePriceProductTransfer(int $priceValue): PriceProductTransfer
    {
        $priceTypeDefault = $this->getFakePriceTypes()[0];
        $priceDimensionTransfer = $this->createFakePriceDimensionTransfer();

        $priceProductTransfer = (new PriceProductTransfer())
            ->setIdPriceProduct(rand())
            ->setFkPriceType($priceTypeDefault->getIdPriceType())
            ->setPriceType($priceTypeDefault)
            ->setPriceDimension($priceDimensionTransfer)
            ->setMoneyValue(
                (new MoneyValueTransfer())
                    ->setFkCurrency(static::FAKE_CURRENCY_ID_1)
                    ->setFkStore(static::FAKE_STORE_ID_1)
                    ->setGrossAmount($priceValue),
            );

        return $priceProductTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    public function createFakePriceDimensionTransfer(): PriceProductDimensionTransfer
    {
        return (new PriceProductDimensionTransfer())
            ->setIdPriceProductDefault(rand())
            ->setType(static::FAKE_PRICE_DIMENSION);
    }

    /**
     * @return array<\Generated\Shared\Transfer\PriceTypeTransfer>
     */
    public function getFakePriceTypes(): array
    {
        return [
            (new PriceTypeTransfer())->setName(static::FAKE_PRICE_TYPE_1)->setIdPriceType(-1),
            (new PriceTypeTransfer())->setName(static::FAKE_PRICE_TYPE_2)->setIdPriceType(-2),
        ];
    }

    /**
     * @return array<\Generated\Shared\Transfer\CurrencyTransfer>
     */
    public function getFakeCurrencies(): array
    {
        return [
            static::FAKE_CURRENCY_ID_1 => (new CurrencyTransfer())->setIdCurrency(static::FAKE_CURRENCY_ID_1),
            static::FAKE_CURRENCY_ID_2 => (new CurrencyTransfer())->setIdCurrency(static::FAKE_CURRENCY_ID_2),
        ];
    }

    /**
     * @param \PHPUnit\Framework\MockObject\Rule\InvokedCount $expectedInvokedCount
     * @param string $method
     * @param array<mixed> ...$arguments
     *
     * @return \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    public function createPriceProductFacadeMockWithExpectations(
        InvokedCount $expectedInvokedCount,
        string $method,
        ...$arguments
    ): ProductMerchantPortalGuiToPriceProductFacadeInterface {
        $priceProductFacadeMock = Stub::makeEmpty(ProductMerchantPortalGuiToPriceProductFacadeInterface::class);

        $priceProductFacadeMock
            ->expects($expectedInvokedCount)
            ->method($method)
            ->with(...$arguments);

        return $priceProductFacadeMock;
    }

    /**
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function createMergeablePriceProductTransfers(): array
    {
        $priceProduct1Transfer = $this->createPriceProductTransfer(
            [
                PriceProductTransfer::VOLUME_QUANTITY => 1,
                PriceProductTransfer::PRICE_TYPE_NAME => static::FAKE_PRICE_TYPE_1,
            ],
            [
                PriceProductDimensionTransfer::TYPE => static::FAKE_PRICE_DIMENSION,
            ],
            [
                MoneyValueTransfer::FK_STORE => static::FAKE_STORE_ID_1,
                MoneyValueTransfer::GROSS_AMOUNT => 17774,
                MoneyValueTransfer::NET_AMOUNT => null,
            ],
            [
                CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR,
            ],
        );

        $priceProduct2Transfer = $this->createPriceProductTransfer(
            [
                PriceProductTransfer::VOLUME_QUANTITY => 1,
                PriceProductTransfer::PRICE_TYPE_NAME => static::FAKE_PRICE_TYPE_1,
            ],
            [
                PriceProductDimensionTransfer::TYPE => static::FAKE_PRICE_DIMENSION,
            ],
            [
                MoneyValueTransfer::FK_STORE => static::FAKE_STORE_ID_1,
                MoneyValueTransfer::GROSS_AMOUNT => null,
                MoneyValueTransfer::NET_AMOUNT => 17774,
            ],
            [
                CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR,
            ],
        );

        return [$priceProduct1Transfer, $priceProduct2Transfer];
    }

    /**
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function createNotMergeablePriceProductTransfers(): array
    {
        $priceProduct1Transfer = $this->createPriceProductTransfer(
            [
                PriceProductTransfer::VOLUME_QUANTITY => 1,
                PriceProductTransfer::PRICE_TYPE_NAME => static::FAKE_PRICE_TYPE_1,
            ],
            [
                PriceProductDimensionTransfer::TYPE => static::FAKE_PRICE_DIMENSION,
            ],
            [
                MoneyValueTransfer::FK_STORE => static::FAKE_STORE_ID_1,
                MoneyValueTransfer::GROSS_AMOUNT => 17774,
                MoneyValueTransfer::NET_AMOUNT => null,
            ],
            [
                CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR,
            ],
        );

        $priceProduct2Transfer = $this->createPriceProductTransfer(
            [
                PriceProductTransfer::VOLUME_QUANTITY => 1,
                PriceProductTransfer::PRICE_TYPE_NAME => static::FAKE_PRICE_TYPE_1,
            ],
            [
                PriceProductDimensionTransfer::TYPE => static::FAKE_PRICE_DIMENSION,
            ],
            [
                MoneyValueTransfer::FK_STORE => static::FAKE_STORE_ID_1,
                MoneyValueTransfer::GROSS_AMOUNT => 18992,
                MoneyValueTransfer::NET_AMOUNT => null,
            ],
            [
                CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR,
            ],
        );

        return [$priceProduct1Transfer, $priceProduct2Transfer];
    }

    /**
     * @param array<string, mixed> $seed
     * @param array<string, mixed> $priceDimensionSeed
     * @param array<string, mixed> $moneyValueSeed
     * @param array<string, mixed> $currencySeed
     * @param array<string, mixed> $priceTypeSeed
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    protected function createPriceProductTransfer(
        array $seed,
        array $priceDimensionSeed = [],
        array $moneyValueSeed = [],
        array $currencySeed = [],
        array $priceTypeSeed = []
    ): PriceProductTransfer {
        $priceProductBuilder = new PriceProductBuilder($seed);
        if ($priceDimensionSeed) {
            $priceDimensionBuilder = new PriceProductDimensionBuilder($priceDimensionSeed);
            $priceProductBuilder->withPriceDimension($priceDimensionBuilder);
        }
        if ($moneyValueSeed) {
            $moneyValueBuilder = $this->createMoneyValueBuilder($moneyValueSeed, $currencySeed);
            $priceProductBuilder->withMoneyValue($moneyValueBuilder);
        }
        if ($priceTypeSeed) {
            $priceTypeBuilder = new PriceTypeBuilder($priceTypeSeed);
            $priceProductBuilder->withPriceType($priceTypeBuilder);
        }

        return $priceProductBuilder->build();
    }

    /**
     * @param array<string, mixed> $moneyValueSeed
     * @param array<string, mixed> $currencySeed
     *
     * @return \Generated\Shared\DataBuilder\MoneyValueBuilder
     */
    protected function createMoneyValueBuilder(array $moneyValueSeed, array $currencySeed = []): MoneyValueBuilder
    {
        $moneyValueBuilder = new MoneyValueBuilder($moneyValueSeed);
        if ($currencySeed) {
            $currencyBuilder = new CurrencyBuilder($currencySeed);
            $moneyValueBuilder->withCurrency($currencyBuilder);
        }

        return $moneyValueBuilder;
    }

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     *
     * @return \Spryker\Zed\ProductMerchantPortalGui\Communication\Reader\PriceProductReaderInterface
     */
    public function createPriceProductReader(
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
    ): PriceProductReaderInterface {
        return new PriceProductReader(
            $priceProductFacade,
            $this->getFactory()->getProductFacade(),
            $this->getFactory()->getPriceProductVolumeFacade(),
            [],
        );
    }

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductMerchantPortalGui\Communication\Form\DataProvider\ProductConcreteEditFormDataProviderInterface
     */
    public function createProductConcreteEditFormDataProvider(
        ProductMerchantPortalGuiToMerchantProductFacadeInterface $merchantProductFacade,
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
    ): ProductConcreteEditFormDataProviderInterface {
        $merchantUserFacade = $this->createMerchantUserFacadeMock(
            [
                'getCurrentMerchantUser' => function () {
                    return (new MerchantUserTransfer())->setIdMerchant(1)->setIdMerchantUser(1);
                },
            ],
        );

        $productFacade = $this->createProductFacadeMock(
            [
                'findProductAbstractById' => function () {
                    return (new ProductAbstractTransfer())->setIdProductAbstract(1);
                },
            ],
        );

        return new ProductConcreteEditFormDataProvider(
            $merchantUserFacade,
            $merchantProductFacade,
            $this->getFactory()->getLocaleFacade(),
            $productFacade,
            $this->getFactory()->createProductAttributeDataProvider(),
            $this->createPriceProductReader($priceProductFacade),
        );
    }
}
