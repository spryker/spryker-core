<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionConditionsTransfer;
use Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer;
use Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface;

class MerchantCommissionExistsMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_DOES_NOT_EXIST = 'merchant_commission.validation.merchant_commission_does_not_exist';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_KEY = '%key%';

    /**
     * @var \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface
     */
    protected MerchantCommissionRepositoryInterface $merchantCommissionRepository;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionDataExtractorInterface
     */
    protected MerchantCommissionDataExtractorInterface $merchantCommissionDataExtractor;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface $merchantCommissionRepository
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionDataExtractorInterface $merchantCommissionDataExtractor
     * @param \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(
        MerchantCommissionRepositoryInterface $merchantCommissionRepository,
        MerchantCommissionDataExtractorInterface $merchantCommissionDataExtractor,
        ErrorAdderInterface $errorAdder
    ) {
        $this->merchantCommissionRepository = $merchantCommissionRepository;
        $this->merchantCommissionDataExtractor = $merchantCommissionDataExtractor;
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantCommissionTransfers): ErrorCollectionTransfer
    {
        $merchantCommissionKeysIndexedByEntityIdentifier = $this->getMerchantCommissionKeysIndexedByMerchantCommissionEntityIdentifier(
            $merchantCommissionTransfers,
        );

        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())->setKeys($merchantCommissionKeysIndexedByEntityIdentifier);
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())
            ->setMerchantCommissionConditions($merchantCommissionConditionsTransfer);
        $merchantCommissionCollectionTransfer = $this->merchantCommissionRepository->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        $existingMerchantCommissionKeys = $this->merchantCommissionDataExtractor->extractMerchantCommissionKeys(
            $merchantCommissionCollectionTransfer->getMerchantCommissions(),
        );

        $nonExistingMerchantCommissionKeysIndexedByEntityIdentifier = array_diff(
            $merchantCommissionKeysIndexedByEntityIdentifier,
            $existingMerchantCommissionKeys,
        );

        $errorCollectionTransfer = new ErrorCollectionTransfer();
        if ($nonExistingMerchantCommissionKeysIndexedByEntityIdentifier === []) {
            return $errorCollectionTransfer;
        }

        foreach ($nonExistingMerchantCommissionKeysIndexedByEntityIdentifier as $entityIdentifier => $merchantCommissionKey) {
            $errorCollectionTransfer = $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_DOES_NOT_EXIST,
                [static::GLOSSARY_KEY_PARAMETER_KEY => $merchantCommissionKey],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return array<string|int, string>
     */
    protected function getMerchantCommissionKeysIndexedByMerchantCommissionEntityIdentifier(ArrayObject $merchantCommissionTransfers): array
    {
        $indexedMerchantCommissionKeys = [];
        foreach ($merchantCommissionTransfers as $entityIdentifier => $merchantCommissionTransfer) {
            $indexedMerchantCommissionKeys[$entityIdentifier] = $merchantCommissionTransfer->getKeyOrFail();
        }

        return $indexedMerchantCommissionKeys;
    }
}
