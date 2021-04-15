<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Laminas\Filter\StringToLower;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;
use Symfony\Component\Form\DataTransformerInterface;

class PriceProductTransformer implements DataTransformerInterface
{
    protected const PRICE_KEY = '%s[%s][%s]';

    /**
     * @var int|null
     */
    protected $idProductAbstract;

    /**
     * @var int|null
     */
    protected $idProductConcrete;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade,
        ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade,
        ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->currencyFacade = $currencyFacade;
        $this->moneyFacade = $moneyFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return $this
     */
    public function setIdProductAbstract(int $idProductAbstract)
    {
        $this->idProductAbstract = $idProductAbstract;

        return $this;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return $this
     */
    public function setIdProductConcrete(int $idProductConcrete)
    {
        $this->idProductConcrete = $idProductConcrete;

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform($value)
    {
        $prices = [];

        $priceTypeTransfers = $this->priceProductFacade->getPriceTypeValues();

        foreach ($value as $priceProductTransfer) {
            if ($priceProductTransfer->getIdPriceProduct()) {
                continue;
            }

            $key = sprintf(
                '%d_%d',
                $priceProductTransfer->getMoneyValue()->getFkStore(),
                $priceProductTransfer->getMoneyValue()->getFkCurrency()
            );

            $prices[$key][PriceProductTableViewTransfer::STORE] = $priceProductTransfer->getMoneyValue()->getFkStore();
            $prices[$key][PriceProductTableViewTransfer::CURRENCY] = $priceProductTransfer->getMoneyValue()->getFkCurrency();

            $prices = $this->addPrices($priceProductTransfer, $priceTypeTransfers, $prices[$key]);
        }

        return $this->utilEncodingService->encodeJson($prices);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function reverseTransform($value)
    {
        $newPriceProducts = $this->utilEncodingService->decodeJson($value, true);
        $priceProductTransfers = new ArrayObject();

        if (!$newPriceProducts) {
            return $priceProductTransfers;
        }

        $priceTypeTransfers = $this->priceProductFacade->getPriceTypeValues();

        foreach ($newPriceProducts as $newPriceProduct) {
            $priceProductTransfers = $this->addPriceProductTransfers(
                $newPriceProduct,
                $priceTypeTransfers,
                $priceProductTransfers
            );
        }

        return $priceProductTransfers;
    }

    /**
     * @phpstan-param array<int, \Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param mixed[] $newPriceProduct
     * @param \Generated\Shared\Transfer\PriceTypeTransfer[] $priceTypeTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function addPriceProductTransfers(
        array $newPriceProduct,
        array $priceTypeTransfers,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        $currency = $newPriceProduct[PriceProductTableViewTransfer::CURRENCY];
        $currencyTransfer = $currency ? $this->currencyFacade->getByIdCurrency($currency) : null;

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            $storeTransfer = (new StoreTransfer())
                ->setIdStore($newPriceProduct[PriceProductTableViewTransfer::STORE]);

            $moneyValueTransfer = (new MoneyValueTransfer())
                ->setCurrency($currencyTransfer)
                ->setStore($storeTransfer)
                ->setFkStore($newPriceProduct[PriceProductTableViewTransfer::STORE])
                ->setFkCurrency($newPriceProduct[PriceProductTableViewTransfer::CURRENCY]);

            $priceTypeName = (new StringToLower())->filter($priceTypeTransfer->getNameOrFail());
            $netAmountKey = $this->createNetKey($priceTypeName);
            $grossAmountKey = $this->createGrossKey($priceTypeName);

            $netAmount = $newPriceProduct[$netAmountKey] ?
                $this->moneyFacade->convertDecimalToInteger((float)$newPriceProduct[$netAmountKey]) : null;
            $grossAmount = $newPriceProduct[$grossAmountKey] ?
                $this->moneyFacade->convertDecimalToInteger((float)$newPriceProduct[$grossAmountKey]) : null;

            $moneyValueTransfer->setNetAmount($netAmount)
                ->setGrossAmount($grossAmount);

            $priceProductTransfers->append(
                (new PriceProductTransfer())
                    ->setIdProductAbstract($this->idProductAbstract)
                    ->setIdProduct($this->idProductConcrete)
                    ->setFkPriceType($priceTypeTransfer->getIdPriceType())
                    ->setPriceType($priceTypeTransfer)
                    ->setMoneyValue($moneyValueTransfer)
            );
        }

        return $priceProductTransfers;
    }

    /**
     * @phpstan-param array<int, \Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer[] $priceTypeTransfers
     * @param mixed[] $prices
     *
     * @return mixed[]
     */
    protected function addPrices(
        PriceProductTransfer $priceProductTransfer,
        array $priceTypeTransfers,
        array $prices
    ): array {
        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            /** @var string $priceTypeName */
            $priceTypeName = $priceTypeTransfer->getName();

            /** @var \Generated\Shared\Transfer\PriceTypeTransfer $currentPriceTypeTransfer */
            $currentPriceTypeTransfer = $priceProductTransfer->getPriceTypeOrFail();
            if ($currentPriceTypeTransfer->getName() !== $priceTypeName) {
                continue;
            }

            $priceTypeName = (new StringToLower())->filter((string)$priceTypeName);
            $netAmountKey = $this->createNetKey($priceTypeName);
            $grossAmountKey = $this->createGrossKey($priceTypeName);
            /** @var \Generated\Shared\Transfer\MoneyValueTransfer $moneyValueTransfer */
            $moneyValueTransfer = $priceProductTransfer->getMoneyValue();

            $prices[$netAmountKey] = $this->moneyFacade->convertIntegerToDecimal((int)$moneyValueTransfer->getNetAmount());
            $prices[$grossAmountKey] = $this->moneyFacade->convertIntegerToDecimal((int)$moneyValueTransfer->getGrossAmount());
        }

        return $prices;
    }

    /**
     * @param string $pryceTypeName
     *
     * @return string
     */
    protected function createGrossKey(string $pryceTypeName): string
    {
        return sprintf(
            static::PRICE_KEY,
            $pryceTypeName,
            PriceProductTransfer::MONEY_VALUE,
            MoneyValueTransfer::GROSS_AMOUNT
        );
    }

    /**
     * @param string $pryceTypeName
     *
     * @return string
     */
    protected function createNetKey(string $pryceTypeName): string
    {
        return sprintf(
            static::PRICE_KEY,
            $pryceTypeName,
            PriceProductTransfer::MONEY_VALUE,
            MoneyValueTransfer::NET_AMOUNT
        );
    }
}
