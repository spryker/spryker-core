<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\MerchantReaderInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface;

class MerchantExistsMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_DOES_NOT_EXIST = 'merchant_commission.validation.merchant_does_not_exist';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_MERCHANT_REFERENCE = '%merchant_reference%';

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\MerchantReaderInterface
     */
    protected MerchantReaderInterface $merchantReader;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface
     */
    protected MerchantDataExtractorInterface $merchantDataExtractor;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\MerchantReaderInterface $merchantReader
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantDataExtractorInterface $merchantDataExtractor
     * @param \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(
        MerchantReaderInterface $merchantReader,
        MerchantDataExtractorInterface $merchantDataExtractor,
        ErrorAdderInterface $errorAdder
    ) {
        $this->merchantReader = $merchantReader;
        $this->merchantDataExtractor = $merchantDataExtractor;
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantCommissionTransfers): ErrorCollectionTransfer
    {
        $merchantReferencesGroupedByEntityIdentifier = $this->getMerchantReferencesGroupedByMerchantCommissionEntityIdentifier(
            $merchantCommissionTransfers,
        );
        $merchantCollectionTransfer = $this->merchantReader->getMerchantCollectionByMerchantReferences(
            $this->getUniqueMerchantReferences($merchantReferencesGroupedByEntityIdentifier),
        );

        $existingMerchantReferences = $this->merchantDataExtractor->extractMerchantReferencesFromMerchantTransfers(
            $merchantCollectionTransfer->getMerchants(),
        );

        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($merchantReferencesGroupedByEntityIdentifier as $entityIdentifier => $merchantReferences) {
            $nonExistingMerchantReferences = array_diff($merchantReferences, $existingMerchantReferences);
            if ($nonExistingMerchantReferences === []) {
                continue;
            }

            $errorCollectionTransfer = $this->addErrorsForNonExistingMerchants(
                $errorCollectionTransfer,
                $nonExistingMerchantReferences,
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
    protected function getMerchantReferencesGroupedByMerchantCommissionEntityIdentifier(ArrayObject $merchantCommissionTransfers): array
    {
        $groupedMerchantReferences = [];
        foreach ($merchantCommissionTransfers as $entityIdentifier => $merchantCommissionTransfer) {
            $groupedMerchantReferences[$entityIdentifier] = $this->merchantDataExtractor->extractMerchantReferencesFromMerchantTransfers(
                $merchantCommissionTransfer->getMerchants(),
            );
        }

        return $groupedMerchantReferences;
    }

    /**
     * @param array<string|int, list<string>> $merchantReferencesGroupedByEntityIdentifier
     *
     * @return list<string>
     */
    protected function getUniqueMerchantReferences(array $merchantReferencesGroupedByEntityIdentifier): array
    {
        return array_unique(array_merge(...$merchantReferencesGroupedByEntityIdentifier));
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param list<string> $nonExistingMerchantReferences
     * @param string|int $entityIdentifier
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function addErrorsForNonExistingMerchants(
        ErrorCollectionTransfer $errorCollectionTransfer,
        array $nonExistingMerchantReferences,
        string|int $entityIdentifier
    ): ErrorCollectionTransfer {
        foreach ($nonExistingMerchantReferences as $merchantReference) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_MERCHANT_DOES_NOT_EXIST,
                [static::GLOSSARY_KEY_PARAMETER_MERCHANT_REFERENCE => $merchantReference],
            );
        }

        return $errorCollectionTransfer;
    }
}
