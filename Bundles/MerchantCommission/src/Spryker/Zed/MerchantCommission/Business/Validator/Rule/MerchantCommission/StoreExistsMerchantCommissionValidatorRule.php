<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Validator\Rule\MerchantCommission;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\MerchantCommission\Business\Extractor\StoreDataExtractorInterface;
use Spryker\Zed\MerchantCommission\Business\Reader\StoreReaderInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface;

class StoreExistsMerchantCommissionValidatorRule implements MerchantCommissionValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST = 'merchant_commission.validation.store_does_not_exist';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_NAME = '%name%';

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Reader\StoreReaderInterface
     */
    protected StoreReaderInterface $storeReader;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Extractor\StoreDataExtractorInterface
     */
    protected StoreDataExtractorInterface $storeDataExtractor;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Reader\StoreReaderInterface $storeReader
     * @param \Spryker\Zed\MerchantCommission\Business\Extractor\StoreDataExtractorInterface $storeDataExtractor
     * @param \Spryker\Zed\MerchantCommission\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(
        StoreReaderInterface $storeReader,
        StoreDataExtractorInterface $storeDataExtractor,
        ErrorAdderInterface $errorAdder
    ) {
        $this->storeReader = $storeReader;
        $this->storeDataExtractor = $storeDataExtractor;
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionTransfer> $merchantCommissionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $merchantCommissionTransfers): ErrorCollectionTransfer
    {
        $storeNamesGroupedByEntityIdentifier = $this->getStoreNamesGroupedByMerchantCommissionEntityIdentifier($merchantCommissionTransfers);
        $storeCollectionTransfers = $this->storeReader->getStoreCollectionByStoreNames(
            $this->getUniqueStoreNames($storeNamesGroupedByEntityIdentifier),
        );

        $existingStoreNames = $this->storeDataExtractor->extractStoreNamesFromStoreTransfers($storeCollectionTransfers->getStores());

        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($storeNamesGroupedByEntityIdentifier as $entityIdentifier => $storeNames) {
            $nonExistingStoreNames = array_diff($storeNames, $existingStoreNames);
            if ($nonExistingStoreNames === []) {
                continue;
            }

            $errorCollectionTransfer = $this->addErrorsForNonExistingStores(
                $errorCollectionTransfer,
                $nonExistingStoreNames,
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
    protected function getStoreNamesGroupedByMerchantCommissionEntityIdentifier(ArrayObject $merchantCommissionTransfers): array
    {
        $groupedStoreNames = [];
        foreach ($merchantCommissionTransfers as $entityIdentifier => $merchantCommissionTransfer) {
            $groupedStoreNames[$entityIdentifier] = $this->storeDataExtractor->extractStoreNamesFromStoreTransfers(
                $merchantCommissionTransfer->getStoreRelationOrFail()->getStores(),
            );
        }

        return $groupedStoreNames;
    }

    /**
     * @param array<string|int, list<string>> $storeNamesGroupedByEntityIdentifier
     *
     * @return list<string>
     */
    protected function getUniqueStoreNames(array $storeNamesGroupedByEntityIdentifier): array
    {
        return array_unique(array_merge(...$storeNamesGroupedByEntityIdentifier));
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param list<string> $nonExistingStoreNames
     * @param string|int $entityIdentifier
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function addErrorsForNonExistingStores(
        ErrorCollectionTransfer $errorCollectionTransfer,
        array $nonExistingStoreNames,
        string|int $entityIdentifier
    ): ErrorCollectionTransfer {
        foreach ($nonExistingStoreNames as $storeName) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST,
                [static::GLOSSARY_KEY_PARAMETER_NAME => $storeName],
            );
        }

        return $errorCollectionTransfer;
    }
}
