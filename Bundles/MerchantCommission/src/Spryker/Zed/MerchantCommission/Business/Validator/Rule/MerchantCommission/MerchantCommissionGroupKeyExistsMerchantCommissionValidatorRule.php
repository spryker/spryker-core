<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionGroupDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionGroupReaderInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface;

class MerchantCommissionGroupKeyExistsMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_GROUP_KEY_DOES_NOT_EXIST = 'merchant_commission.validation.merchant_commission_group_key_does_not_exist';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_KEY = '%key%';

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionGroupReaderInterface
     */
    protected MerchantCommissionGroupReaderInterface $merchantCommissionGroupReader;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionGroupDataExtractorInterface
     */
    protected MerchantCommissionGroupDataExtractorInterface $merchantCommissionGroupDataExtractor;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\MerchantCommissionGroupReaderInterface $merchantCommissionGroupReader
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionGroupDataExtractorInterface $merchantCommissionGroupDataExtractor
     * @param \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(
        MerchantCommissionGroupReaderInterface $merchantCommissionGroupReader,
        MerchantCommissionGroupDataExtractorInterface $merchantCommissionGroupDataExtractor,
        ErrorAdderInterface $errorAdder
    ) {
        $this->merchantCommissionGroupReader = $merchantCommissionGroupReader;
        $this->merchantCommissionGroupDataExtractor = $merchantCommissionGroupDataExtractor;
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantCommissionTransfers): ErrorCollectionTransfer
    {
        $merchantCommissionGroupKeysIndexedByEntityIdentifier = $this->getMerchantCommissionGroupKeysIndexedByMerchantCommissionEntityIdentifier(
            $merchantCommissionTransfers,
        );
        /** @var list<string> $merchantCommissionGroupKeys */
        $merchantCommissionGroupKeys = array_unique($merchantCommissionGroupKeysIndexedByEntityIdentifier);
        $merchantCommissionGroupCollectionTransfer = $this->merchantCommissionGroupReader->getMerchantCommissionGroupCollectionByKeys(
            $merchantCommissionGroupKeys,
        );

        $existingMerchantCommissionGroupKeys = $this->merchantCommissionGroupDataExtractor->extractUniqueMerchantCommissionGroupKeys(
            $merchantCommissionGroupCollectionTransfer->getMerchantCommissionGroups(),
        );

        $nonExistingMerchantCommissionGroupKeyssIndexedEntityIdentifier = array_diff(
            $merchantCommissionGroupKeysIndexedByEntityIdentifier,
            $existingMerchantCommissionGroupKeys,
        );

        return $this->addErrorsForNonExistingMerchantCommissionGroups(
            new ErrorCollectionTransfer(),
            $nonExistingMerchantCommissionGroupKeyssIndexedEntityIdentifier,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return array<string|int, string>
     */
    protected function getMerchantCommissionGroupKeysIndexedByMerchantCommissionEntityIdentifier(
        ArrayObject $merchantCommissionTransfers
    ): array {
        $indexedMerchantCommissionGroupUuids = [];
        foreach ($merchantCommissionTransfers as $entityIdentifier => $merchantCommissionTransfer) {
            $merchantCommissionGroupTransfer = $merchantCommissionTransfer->getMerchantCommissionGroupOrFail();
            $indexedMerchantCommissionGroupUuids[$entityIdentifier] = $merchantCommissionGroupTransfer->getKeyOrFail();
        }

        return $indexedMerchantCommissionGroupUuids;
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param array<string|int, string> $nonExistingMerchantCommissionGroupUuidsIndexedByEntityIdentifier
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function addErrorsForNonExistingMerchantCommissionGroups(
        ErrorCollectionTransfer $errorCollectionTransfer,
        array $nonExistingMerchantCommissionGroupUuidsIndexedByEntityIdentifier
    ): ErrorCollectionTransfer {
        foreach ($nonExistingMerchantCommissionGroupUuidsIndexedByEntityIdentifier as $entityIdentifier => $key) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_GROUP_KEY_DOES_NOT_EXIST,
                [static::GLOSSARY_KEY_PARAMETER_KEY => $key],
            );
        }

        return $errorCollectionTransfer;
    }
}
