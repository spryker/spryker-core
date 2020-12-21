<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductOfferTableViewTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface;
use Symfony\Component\Form\DataTransformerInterface;

class PriceProductOfferTransformer implements DataTransformerInterface
{
    /**
     * @var int|null
     */
    protected $idProductOffer;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Service\ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMoneyFacadeInterface $moneyFacade
     * @param int|null $idProductOffer
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService,
        ProductOfferMerchantPortalGuiToPriceProductFacadeInterface $priceProductFacade,
        ProductOfferMerchantPortalGuiToCurrencyFacadeInterface $currencyFacade,
        ProductOfferMerchantPortalGuiToMoneyFacadeInterface $moneyFacade,
        ?int $idProductOffer = null
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->priceProductFacade = $priceProductFacade;
        $this->currencyFacade = $currencyFacade;
        $this->moneyFacade = $moneyFacade;
        $this->idProductOffer = $idProductOffer;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform($value)
    {
        $prices = [];

        foreach ($value as $priceProductTransfer) {
            if ($priceProductTransfer->getIdPriceProduct()) {
                continue;
            }

            $key = sprintf(
                '%d_%d',
                $priceProductTransfer->getMoneyValue()->getFkStore(),
                $priceProductTransfer->getMoneyValue()->getFkCurrency()
            );

            $prices[$key][PriceProductOfferTableViewTransfer::STORE] = $priceProductTransfer->getMoneyValue()->getFkStore();
            $prices[$key][PriceProductOfferTableViewTransfer::CURRENCY] = $priceProductTransfer->getMoneyValue()->getFkCurrency();

            $prices = $this->addPrices($priceProductTransfer, $prices[$key]);
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
        $newPriceProductOffers = $this->utilEncodingService->decodeJson($value, true);
        $priceProductTransfers = new ArrayObject();
        $priceProductDimensionTransfer = (new PriceProductDimensionTransfer())
            ->setIdProductOffer($this->idProductOffer ?? 0);

        if (!$newPriceProductOffers) {
            return $priceProductTransfers;
        }

        foreach ($newPriceProductOffers as $newPriceProductOffer) {
            $priceProductTransfers = $this->addPriceProductTransfers(
                $newPriceProductOffer,
                $priceProductDimensionTransfer,
                $priceProductTransfers
            );
        }

        return $priceProductTransfers;
    }

    /**
     * @phpstan-param array<mixed> $newPriceProductOffer
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\PriceProductTransfer>
     *
     * @param array $newPriceProductOffer
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function addPriceProductTransfers(
        array $newPriceProductOffer,
        PriceProductDimensionTransfer $priceProductDimensionTransfer,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        $currency = $newPriceProductOffer[PriceProductOfferTableViewTransfer::CURRENCY];
        $currencyTransfer = $currency ?
            $this->currencyFacade->getByIdCurrency($currency)
            : null;

        foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
            $storeTransfer = (new StoreTransfer())
                ->setIdStore($newPriceProductOffer[PriceProductOfferTableViewTransfer::STORE]);

            $moneyValueTransfer = (new MoneyValueTransfer())
                ->setCurrency($currencyTransfer)
                ->setStore($storeTransfer)
                ->setFkStore($newPriceProductOffer[PriceProductOfferTableViewTransfer::STORE])
                ->setFkCurrency($newPriceProductOffer[PriceProductOfferTableViewTransfer::CURRENCY]);

            $priceTypeName = mb_strtolower((string)$priceTypeTransfer->getName());
            $netAmountKey = $this->createNetKey($priceTypeName);
            $grossAmountKey = $this->createGrossKey($priceTypeName);

            $netAmount = $newPriceProductOffer[$netAmountKey] ?
                $this->moneyFacade->convertDecimalToInteger((float)$newPriceProductOffer[$netAmountKey]) : null;
            $grossAmount = $newPriceProductOffer[$grossAmountKey] ?
                $this->moneyFacade->convertDecimalToInteger((float)$newPriceProductOffer[$grossAmountKey]) : null;

            $moneyValueTransfer->setNetAmount($netAmount)
                ->setGrossAmount($grossAmount);

            $priceProductTransfers->append(
                (new PriceProductTransfer())
                    ->setFkPriceType($priceTypeTransfer->getIdPriceType())
                    ->setPriceType($priceTypeTransfer)
                    ->setMoneyValue($moneyValueTransfer)
                    ->setPriceDimension($priceProductDimensionTransfer)
            );
        }

        return $priceProductTransfers;
    }

    /**
     * @phpstan-param array<mixed> $prices
     *
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param array $prices
     *
     * @return array
     */
    protected function addPrices(PriceProductTransfer $priceProductTransfer, array $prices): array
    {
        foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
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
