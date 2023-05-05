<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Business\Validator\Rule;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\ShipmentType\Business\Extractor\StoreDataExtractorInterface;
use Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface;
use Spryker\Zed\ShipmentType\Dependency\Facade\ShipmentTypeToStoreFacadeInterface;

class StoreExistsShipmentTypeValidatorRule implements ShipmentTypeValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST = 'shipment_type.validation.store_does_not_exist';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAMETER_NAME = '%name%';

    /**
     * @var \Spryker\Zed\ShipmentType\Dependency\Facade\ShipmentTypeToStoreFacadeInterface
     */
    protected ShipmentTypeToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\ShipmentType\Business\Extractor\StoreDataExtractorInterface
     */
    protected StoreDataExtractorInterface $storeDataExtractor;

    /**
     * @var \Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface
     */
    protected ValidationErrorCreatorInterface $validationErrorCreator;

    /**
     * @param \Spryker\Zed\ShipmentType\Dependency\Facade\ShipmentTypeToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ShipmentType\Business\Extractor\StoreDataExtractorInterface $storeDataExtractor
     * @param \Spryker\Zed\ShipmentType\Business\Validator\ErrorCreator\ValidationErrorCreatorInterface $validationErrorCreator
     */
    public function __construct(
        ShipmentTypeToStoreFacadeInterface $storeFacade,
        StoreDataExtractorInterface $storeDataExtractor,
        ValidationErrorCreatorInterface $validationErrorCreator
    ) {
        $this->storeFacade = $storeFacade;
        $this->storeDataExtractor = $storeDataExtractor;
        $this->validationErrorCreator = $validationErrorCreator;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $shipmentTypeTransfers, ErrorCollectionTransfer $errorCollectionTransfer): ErrorCollectionTransfer
    {
        $groupedStoreNames = $this->getStoreNamesGroupedByShipmentTypeEntityIdentifier($shipmentTypeTransfers);

        $existingStoreTransfers = $this->storeFacade->getStoreTransfersByStoreNames(array_unique(array_merge(...$groupedStoreNames)));
        $existingStoreNames = $this->storeDataExtractor->extractStoreNamesFromStoreTransfers($existingStoreTransfers);

        foreach ($groupedStoreNames as $entityIdentifier => $storeNames) {
            $nonExistingStoreNames = array_diff($storeNames, $existingStoreNames);
            if ($nonExistingStoreNames === []) {
                continue;
            }

            $errorCollectionTransfer = $this->addErrorsForNonExistingStores($errorCollectionTransfer, $nonExistingStoreNames, $entityIdentifier);
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ShipmentTypeTransfer> $shipmentTypeTransfers
     *
     * @return array<string|int, list<string>>
     */
    protected function getStoreNamesGroupedByShipmentTypeEntityIdentifier(ArrayObject $shipmentTypeTransfers): array
    {
        $groupedStoreNames = [];
        foreach ($shipmentTypeTransfers as $entityIdentifier => $shipmentTypeTransfer) {
            $groupedStoreNames[$entityIdentifier] = $this->storeDataExtractor->extractStoreNamesFromStoreTransfers(
                $shipmentTypeTransfer->getStoreRelationOrFail()->getStores(),
            );
        }

        return $groupedStoreNames;
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
        foreach ($nonExistingStoreNames as $nonExistingStoreName) {
            $errorCollectionTransfer->addError(
                $this->validationErrorCreator->createValidationError(
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST,
                    [static::GLOSSARY_KEY_PARAMETER_NAME => $nonExistingStoreName],
                ),
            );
        }

        return $errorCollectionTransfer;
    }
}
