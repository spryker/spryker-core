<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductAbstractTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;
use Symfony\Component\Form\DataTransformerInterface;

class PriceProductTransformer implements DataTransformerInterface
{
    /**
     * @var int
     */
    protected $idProductAbstract;

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
     * @param int $idProductAbstract
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        int $idProductAbstract,
        ProductMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade,
        ProductMerchantPortalGuiToMoneyFacadeInterface $moneyFacade,
        ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->idProductAbstract = $idProductAbstract;
        $this->priceProductFacade = $priceProductFacade;
        $this->currencyFacade = $currencyFacade;
        $this->moneyFacade = $moneyFacade;
        $this->utilEncodingService = $utilEncodingService;
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

            $prices[$key][PriceProductAbstractTableViewTransfer::STORE] = $priceProductTransfer->getMoneyValue()->getFkStore();
            $prices[$key][PriceProductAbstractTableViewTransfer::CURRENCY] = $priceProductTransfer->getMoneyValue()->getFkCurrency();

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
     * @phpstan-param array<mixed> $newPriceProduct
     * @phpstan-param array<int, \Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param array $newPriceProduct
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
        $currency = $newPriceProduct[PriceProductAbstractTableViewTransfer::CURRENCY];
        $currencyTransfer = $currency ? $this->currencyFacade->getByIdCurrency($currency) : null;

        foreach ($priceTypeTransfers as $priceTypeTransfer) {
            $storeTransfer = (new StoreTransfer())
                ->setIdStore($newPriceProduct[PriceProductAbstractTableViewTransfer::STORE]);

            $moneyValueTransfer = (new MoneyValueTransfer())
                ->setCurrency($currencyTransfer)
                ->setStore($storeTransfer)
                ->setFkStore($newPriceProduct[PriceProductAbstractTableViewTransfer::STORE])
                ->setFkCurrency($newPriceProduct[PriceProductAbstractTableViewTransfer::CURRENCY]);

            $priceTypeName = mb_strtolower($priceTypeTransfer->getName());
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
                    ->setFkPriceType($priceTypeTransfer->getIdPriceType())
                    ->setPriceType($priceTypeTransfer)
                    ->setMoneyValue($moneyValueTransfer)
            );
        }

        return $priceProductTransfers;
    }

    /**
     * @phpstan-param array<int, \Generated\Shared\Transfer\PriceTypeTransfer> $priceTypeTransfers
     * @phpstan-param array<mixed> $prices
     *
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceTypeTransfer[] $priceTypeTransfers
     * @param array $prices
     *
     * @return array
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

            $priceTypeName = mb_strtolower((string)$priceTypeName);
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
            '%s[%s][%s]',
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
            '%s[%s][%s]',
            $pryceTypeName,
            PriceProductTransfer::MONEY_VALUE,
            MoneyValueTransfer::NET_AMOUNT
        );
    }
}
