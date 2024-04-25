<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Expander;

use ArrayObject;
use Spryker\Zed\MerchantCommission\Business\Extractor\CurrencyDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\CurrencyReaderInterface;

class MerchantCommissionAmountExpander implements MerchantCommissionAmountExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\CurrencyReaderInterface
     */
    protected CurrencyReaderInterface $currencyReader;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\CurrencyDataExtractorInterface
     */
    protected CurrencyDataExtractorInterface $currencyDataExtractor;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\CurrencyReaderInterface $currencyReader
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\CurrencyDataExtractorInterface $currencyDataExtractor
     */
    public function __construct(CurrencyReaderInterface $currencyReader, CurrencyDataExtractorInterface $currencyDataExtractor)
    {
        $this->currencyReader = $currencyReader;
        $this->currencyDataExtractor = $currencyDataExtractor;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer> $merchantCommissionAmountTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionAmountTransfer>
     */
    public function expandMerchantCommissionAmountsWithCurrency(ArrayObject $merchantCommissionAmountTransfers): ArrayObject
    {
        $currencyCodes = $this->currencyDataExtractor->extractCurrencyCodesFromMerchantCommissionAmountTransfers(
            $merchantCommissionAmountTransfers,
        );

        $currencyCollectionTransfer = $this->currencyReader->getCurrencyCollectionByCodes($currencyCodes);
        $currencyTransfersIndexedByCode = $this->getCurrencyTransfersIndexedByCode(
            $currencyCollectionTransfer->getCurrencies(),
        );

        foreach ($merchantCommissionAmountTransfers as $merchantCommissionAmountTransfer) {
            $currencyCode = $merchantCommissionAmountTransfer->getCurrencyOrFail()->getCodeOrFail();
            $currencyTransfer = $currencyTransfersIndexedByCode[$currencyCode] ?? null;
            if (!$currencyTransfer) {
                continue;
            }

            $merchantCommissionAmountTransfer->setCurrency($currencyTransfer);
        }

        return $merchantCommissionAmountTransfers;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CurrencyTransfer> $currencyTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\CurrencyTransfer>
     */
    protected function getCurrencyTransfersIndexedByCode(ArrayObject $currencyTransfers): array
    {
        $indexedCurrencyTransfers = [];
        foreach ($currencyTransfers as $currencyTransfer) {
            $indexedCurrencyTransfers[$currencyTransfer->getCodeOrFail()] = $currencyTransfer;
        }

        return $indexedCurrencyTransfers;
    }
}
