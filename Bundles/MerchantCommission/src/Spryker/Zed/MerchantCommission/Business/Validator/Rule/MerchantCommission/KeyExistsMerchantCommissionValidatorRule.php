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
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\MerchantCommission\Business\Extractor\MerchantCommissionDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface;

class KeyExistsMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_EXISTS = 'merchant_commission.validation.merchant_commission_key_exists';

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
        $merchantCommissionKeys = $this->merchantCommissionDataExtractor->extractMerchantCommissionKeys(
            $merchantCommissionTransfers,
        );
        $merchantCommissionConditionsTransfer = (new MerchantCommissionConditionsTransfer())->setKeys($merchantCommissionKeys);
        $merchantCommissionCriteriaTransfer = (new MerchantCommissionCriteriaTransfer())->setMerchantCommissionConditions(
            $merchantCommissionConditionsTransfer,
        );
        $merchantCommissionCollectionTransfer = $this->merchantCommissionRepository->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        $errorCollectionTransfer = new ErrorCollectionTransfer();
        if ($merchantCommissionCollectionTransfer->getMerchantCommissions()->count() === 0) {
            return $errorCollectionTransfer;
        }

        $existingMerchantCommissionKeysIndexedByUuid = $this->getMerchantCommissionKeysIndexedByUuid(
            $merchantCommissionCollectionTransfer->getMerchantCommissions(),
        );

        foreach ($merchantCommissionTransfers as $entityIdentifier => $merchantCommissionTransfer) {
            if (!$this->merchantCommissionKeyExists($merchantCommissionTransfer, $existingMerchantCommissionKeysIndexedByUuid)) {
                continue;
            }

            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_MERCHANT_COMMISSION_KEY_EXISTS,
                [static::GLOSSARY_KEY_PARAMETER_KEY => $merchantCommissionTransfer->getKeyOrFail()],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param array<string, string> $existingMerchantCommissionKeysIndexedByUuid
     *
     * @return bool
     */
    protected function merchantCommissionKeyExists(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        array $existingMerchantCommissionKeysIndexedByUuid
    ): bool {
        if (!in_array($merchantCommissionTransfer->getKeyOrFail(), $existingMerchantCommissionKeysIndexedByUuid, true)) {
            return false;
        }

        if ($merchantCommissionTransfer->getUuid() === null) {
            return true;
        }

        $merchantCommissionKey = $existingMerchantCommissionKeysIndexedByUuid[$merchantCommissionTransfer->getUuidOrFail()] ?? null;
        if ($merchantCommissionTransfer->getKeyOrFail() === $merchantCommissionKey) {
            return false;
        }

        return true;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return array<string, string>
     */
    protected function getMerchantCommissionKeysIndexedByUuid(ArrayObject $merchantCommissionTransfers): array
    {
        $indexedMerchantCommissionKeys = [];
        foreach ($merchantCommissionTransfers as $merchantCommissionTransfer) {
            $indexedMerchantCommissionKeys[$merchantCommissionTransfer->getUuidOrFail()] = $merchantCommissionTransfer->getKeyOrFail();
        }

        return $indexedMerchantCommissionKeys;
    }
}
