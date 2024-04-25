<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\MerchantCommission\Business\Extractor\CurrencyDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\CurrencyReaderInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface;

class CurrencyExistsMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CURRENCY_DOES_NOT_EXIST = 'merchant_commission.validation.currency_does_not_exist';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_CODE = '%code%';

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\CurrencyReaderInterface
     */
    protected CurrencyReaderInterface $currencyReader;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\CurrencyDataExtractorInterface
     */
    protected CurrencyDataExtractorInterface $currencyDataExtractor;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\CurrencyReaderInterface $currencyReader
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\CurrencyDataExtractorInterface $currencyDataExtractor
     * @param \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(
        CurrencyReaderInterface $currencyReader,
        CurrencyDataExtractorInterface $currencyDataExtractor,
        ErrorAdderInterface $errorAdder
    ) {
        $this->currencyReader = $currencyReader;
        $this->currencyDataExtractor = $currencyDataExtractor;
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantCommissionTransfers): ErrorCollectionTransfer
    {
        $currencyCodesGroupedByEntityIdentifier = $this->getCurrencyCodesGroupedByMerchantCommissionEntityIdentifier(
            $merchantCommissionTransfers,
        );

        $currencyCollectionTransfer = $this->currencyReader->getCurrencyCollectionByCodes(
            $this->getUniqueCurrencyCodes($currencyCodesGroupedByEntityIdentifier),
        );
        $existingCurrencyCodes = $this->currencyDataExtractor->extractCurrencyCodes($currencyCollectionTransfer->getCurrencies());

        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($currencyCodesGroupedByEntityIdentifier as $entityIdentifier => $currencyCodes) {
            $nonExistingCurrencyCodes = array_diff($currencyCodes, $existingCurrencyCodes);
            if ($nonExistingCurrencyCodes === []) {
                continue;
            }

            $this->addErrorsForNonExistingCurrencies(
                $errorCollectionTransfer,
                $nonExistingCurrencyCodes,
                $entityIdentifier,
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return array<string|int, list<string>>
     */
    protected function getCurrencyCodesGroupedByMerchantCommissionEntityIdentifier(ArrayObject $merchantCommissionTransfers): array
    {
        $groupedCurrencyCodes = [];
        foreach ($merchantCommissionTransfers as $entityIdentifier => $merchantCommissionTransfer) {
            if ($merchantCommissionTransfer->getMerchantCommissionAmounts()->count() === 0) {
                continue;
            }

            $groupedCurrencyCodes[$entityIdentifier] = $this->currencyDataExtractor->extractCurrencyCodesFromMerchantCommissionAmountTransfers(
                $merchantCommissionTransfer->getMerchantCommissionAmounts(),
            );
        }

        return $groupedCurrencyCodes;
    }

    /**
     * @param array<string|int, list<string>> $currencyCodesGroupedByEntityIdentifier
     *
     * @return list<string>
     */
    protected function getUniqueCurrencyCodes(array $currencyCodesGroupedByEntityIdentifier): array
    {
        return array_unique(array_merge(...$currencyCodesGroupedByEntityIdentifier));
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param list<string> $nonExistingCurrencyCodes
     * @param string|int $entityIdentifier
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function addErrorsForNonExistingCurrencies(
        ErrorCollectionTransfer $errorCollectionTransfer,
        array $nonExistingCurrencyCodes,
        string|int $entityIdentifier
    ): ErrorCollectionTransfer {
        foreach ($nonExistingCurrencyCodes as $currencyCode) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_CURRENCY_DOES_NOT_EXIST,
                [static::GLOSSARY_KEY_PARAMETER_CODE => $currencyCode],
            );
        }

        return $errorCollectionTransfer;
    }
}
