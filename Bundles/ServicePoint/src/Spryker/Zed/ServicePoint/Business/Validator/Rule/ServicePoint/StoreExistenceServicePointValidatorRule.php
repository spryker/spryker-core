<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractorInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToStoreFacadeInterface;

class StoreExistenceServicePointValidatorRule implements ServicePointValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST = 'service_point.validation.store_does_not_exist';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_NAME = '%name%';

    /**
     * @var \Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToStoreFacadeInterface
     */
    protected ServicePointToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractorInterface
     */
    protected ServicePointStoreExtractorInterface $servicePointStoreExtractor;

    /**
     * @param \Spryker\Zed\ServicePoint\Dependency\Facade\ServicePointToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ServicePoint\Business\Validator\Util\ErrorAdderInterface $errorAdder
     * @param \Spryker\Zed\ServicePoint\Business\Extractor\ServicePointStoreExtractorInterface $servicePointStoreExtractor
     */
    public function __construct(
        ServicePointToStoreFacadeInterface $storeFacade,
        ErrorAdderInterface $errorAdder,
        ServicePointStoreExtractorInterface $servicePointStoreExtractor
    ) {
        $this->storeFacade = $storeFacade;
        $this->errorAdder = $errorAdder;
        $this->servicePointStoreExtractor = $servicePointStoreExtractor;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $servicePointTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        $storeRelationTransfersIndexedByEntityIdentifier = $this->getStoreRelationTransfersIndexedByEntityIdentifier(
            $servicePointTransfers,
        );
        $storeNamesGroupedByEntityIdentifier = $this->getStoreNamesGroupedByEntityIdentifier(
            $storeRelationTransfersIndexedByEntityIdentifier,
        );
        $uniqueStoreNames = $this->extractUniqueStoreNames($storeNamesGroupedByEntityIdentifier);
        $storeTransfers = $this->storeFacade->getStoreTransfersByStoreNames($uniqueStoreNames);

        $existingStoreNames = $this->servicePointStoreExtractor->extractStoreNamesFromStoreRelationTransfer(
            (new StoreRelationTransfer())->setStores(new ArrayObject($storeTransfers)),
        );

        return $this->addErrorsForNonExistingStores(
            $errorCollectionTransfer,
            $storeNamesGroupedByEntityIdentifier,
            $existingStoreNames,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    public function isTerminated(
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        return $postValidationErrorTransfers->count() > $initialErrorTransfers->count();
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param array<string|int, array<string>> $storeNamesGroupedByEntityIdentifier
     * @param list<string> $existingStoreNames
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function addErrorsForNonExistingStores(
        ErrorCollectionTransfer $errorCollectionTransfer,
        array $storeNamesGroupedByEntityIdentifier,
        array $existingStoreNames
    ): ErrorCollectionTransfer {
        foreach ($storeNamesGroupedByEntityIdentifier as $entityIdentifier => $storeNames) {
            $nonExistingStoreNames = array_diff($storeNames, $existingStoreNames);

            if ($nonExistingStoreNames) {
                $errorCollectionTransfer = $this->addErrorMessagesToErrorCollection(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    $nonExistingStoreNames,
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param string|int $entityIdentifier
     * @param list<string> $nonExistingStoreNames
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function addErrorMessagesToErrorCollection(
        ErrorCollectionTransfer $errorCollectionTransfer,
        int|string $entityIdentifier,
        array $nonExistingStoreNames
    ): ErrorCollectionTransfer {
        foreach ($nonExistingStoreNames as $name) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST,
                [
                    static::GLOSSARY_KEY_PARAMETER_NAME => $name,
                ],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param array<string|int, list<string>> $storeNamesGroupedByEntityIdentifier
     *
     * @return array<int, string>
     */
    protected function extractUniqueStoreNames(array $storeNamesGroupedByEntityIdentifier): array
    {
        $storeNames = [];

        foreach ($storeNamesGroupedByEntityIdentifier as $storeNamesByEntityIdentifier) {
            $storeNames[] = $storeNamesByEntityIdentifier;
        }

        return array_unique(array_merge(...$storeNames));
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreRelationTransfer> $storeRelationTransfers
     *
     * @return array<int|string, list<string>>
     */
    protected function getStoreNamesGroupedByEntityIdentifier(array $storeRelationTransfers): array
    {
        $storeNamesGroupedByEntityIdentifier = [];

        foreach ($storeRelationTransfers as $entityIdentifier => $storeRelationTransfer) {
            $storeNamesGroupedByEntityIdentifier[$entityIdentifier] = $this->servicePointStoreExtractor
                ->extractStoreNamesFromStoreRelationTransfer($storeRelationTransfer);
        }

        return $storeNamesGroupedByEntityIdentifier;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     *
     * @return array<string|int, \Generated\Shared\Transfer\StoreRelationTransfer>
     */
    protected function getStoreRelationTransfersIndexedByEntityIdentifier(ArrayObject $servicePointTransfers): array
    {
        $storeRelationTransfers = [];

        foreach ($servicePointTransfers as $entityIdentifier => $servicePointTransfer) {
            $storeRelationTransfers[$entityIdentifier] = $servicePointTransfer->getStoreRelationOrFail();
        }

        return $storeRelationTransfers;
    }
}
