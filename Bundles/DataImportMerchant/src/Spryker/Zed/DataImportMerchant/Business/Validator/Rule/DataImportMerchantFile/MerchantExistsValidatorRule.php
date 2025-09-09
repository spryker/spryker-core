<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Validator\Rule\DataImportMerchantFile;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\DataImportMerchant\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToMerchantFacadeInterface;

class MerchantExistsValidatorRule implements DataImportMerchantFileValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_NOT_FOUND = 'data_import_merchant.validation.merchant_not_found';

    /**
     * @param \Spryker\Zed\DataImportMerchant\Dependency\Facade\DataImportMerchantToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(protected DataImportMerchantToMerchantFacadeInterface $merchantFacade)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer
     */
    public function validate(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): DataImportMerchantFileCollectionResponseTransfer {
        $merchantReferences = $this->extractMerchantReferences($dataImportMerchantFileCollectionResponseTransfer);
        $existingMerchantReferences = $this->getExistingMerchantReferences($merchantReferences);
        $index = 0;

        foreach ($dataImportMerchantFileCollectionResponseTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            if (!in_array($dataImportMerchantFileTransfer->getMerchantReferenceOrFail(), $existingMerchantReferences, true)) {
                $entityIdentifier = $dataImportMerchantFileTransfer->getUuid() ?? (string)$index;
                $errorTransfer = (new ErrorTransfer())
                    ->setMessage(static::GLOSSARY_KEY_VALIDATION_MERCHANT_NOT_FOUND)
                    ->setEntityIdentifier($entityIdentifier);

                $dataImportMerchantFileCollectionResponseTransfer->addError($errorTransfer);
            }
            $index++;
        }

        return $dataImportMerchantFileCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
     *
     * @return list<string>
     */
    protected function extractMerchantReferences(
        DataImportMerchantFileCollectionResponseTransfer $dataImportMerchantFileCollectionResponseTransfer
    ): array {
        $merchantReferences = [];
        foreach ($dataImportMerchantFileCollectionResponseTransfer->getDataImportMerchantFiles() as $dataImportMerchantFileTransfer) {
            $merchantReferences[] = $dataImportMerchantFileTransfer->getMerchantReferenceOrFail();
        }

        return array_unique($merchantReferences);
    }

    /**
     * @param list<string> $merchantReferences
     *
     * @return list<string>
     */
    protected function getExistingMerchantReferences(array $merchantReferences): array
    {
        if (!$merchantReferences) {
            return [];
        }

        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setMerchantReferences($merchantReferences);

        $merchantCollectionTransfer = $this->merchantFacade->get($merchantCriteriaTransfer);

        $existingMerchantReferences = [];
        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $existingMerchantReferences[] = $merchantTransfer->getMerchantReferenceOrFail();
        }

        return $existingMerchantReferences;
    }
}
