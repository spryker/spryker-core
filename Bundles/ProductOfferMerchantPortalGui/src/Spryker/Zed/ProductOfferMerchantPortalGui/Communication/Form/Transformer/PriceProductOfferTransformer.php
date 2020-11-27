<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\Transformer;

use ArrayObject;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
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
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\AbstractPriceProductOfferGuiTableConfigurationProvider::COL_STORE
     */
    protected const KEY_STORE = 'store';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\AbstractPriceProductOfferGuiTableConfigurationProvider::COL_CURRENCY
     */
    protected const KEY_CURRENCY = 'currency';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\AbstractPriceProductOfferGuiTableConfigurationProvider::ID_COLUMN_SUFFIX_PRICE_TYPE_NET
     */
    protected const SUFFIX_PRYCE_TYPE_NET = '[moneyValue][netAmount]';

    /**
     * @uses \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\ConfigurationProvider\AbstractPriceProductOfferGuiTableConfigurationProvider::ID_COLUMN_SUFFIX_PRICE_TYPE_GROSS
     */
    protected const SUFFIX_PRYCE_TYPE_GROSS = '[moneyValue][grossAmount]';

    /**
     * @var int
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

            $prices[$key][static::KEY_STORE] = $priceProductTransfer->getMoneyValue()->getFkStore();
            $prices[$key][static::KEY_CURRENCY] = $priceProductTransfer->getMoneyValue()->getFkCurrency();

            foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
                if ($priceProductTransfer->getPryceType()->getName() !== $priceTypeTransfer->getName()) {
                    continue;
                }

                $pryceTypeName = mb_strtolower($priceTypeTransfer->getName());
                $netAmountKey = $pryceTypeName . static::SUFFIX_PRYCE_TYPE_NET;
                $grossAmountKey = $pryceTypeName . static::SUFFIX_PRYCE_TYPE_GROSS;

                $prices[$key][$netAmountKey] = $this->moneyFacade->convertIntegerToDecimal($priceProductTransfer->getMoneyValue()->getNetAmount());
                $prices[$key][$grossAmountKey] = $this->moneyFacade->convertIntegerToDecimal($priceProductTransfer->getMoneyValue()->getGrossAmount());
            }
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

        if ($newPriceProductOffers) {
            foreach ($newPriceProductOffers as $newPriceProductOffer) {
                $currencyTransfer = $this->currencyFacade->getByIdCurrency($newPriceProductOffer[static::KEY_CURRENCY]);

                $priceProductTransfers = $this->addPriceProductTransfers(
                    $newPriceProductOffer,
                    $currencyTransfer,
                    $priceProductDimensionTransfer,
                    $priceProductTransfers
                );
            }
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
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function addPriceProductTransfers(
        array $newPriceProductOffer,
        CurrencyTransfer $currencyTransfer,
        PriceProductDimensionTransfer $priceProductDimensionTransfer,
        ArrayObject $priceProductTransfers
    ): ArrayObject {
        foreach ($this->priceProductFacade->getPriceTypeValues() as $priceTypeTransfer) {
            $storeTransfer = (new StoreTransfer())
                ->setIdStore($newPriceProductOffer[static::KEY_STORE]);

            $moneyValueTransfer = (new MoneyValueTransfer())
                ->setCurrency($currencyTransfer)
                ->setStore($storeTransfer)
                ->setFkStore($newPriceProductOffer[static::KEY_STORE])
                ->setFkCurrency($newPriceProductOffer[static::KEY_CURRENCY]);

            $pryceTypeName = mb_strtolower($priceTypeTransfer->getName());
            $netAmountKey = $pryceTypeName . static::SUFFIX_PRYCE_TYPE_NET;
            $grossAmountKey = $pryceTypeName . static::SUFFIX_PRYCE_TYPE_GROSS;

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
}
