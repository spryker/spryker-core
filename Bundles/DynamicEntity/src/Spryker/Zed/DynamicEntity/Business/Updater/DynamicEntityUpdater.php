<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Updater;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityFieldConditionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityCollectionRequestBuilderInterface;
use Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityConfigurationNotFoundException;
use Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface;

class DynamicEntityUpdater implements DynamicEntityUpdaterInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CONFIGURATION_NOT_FOUND = 'dynamic_entity.validation.configuration_not_found';

    /**
     * @var string
     */
    protected const FIELD_IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    protected const PLACEHOLDER_ALIAS_NAME = '%aliasName%';

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface
     */
    protected DynamicEntityReaderInterface $dynamicEntityReader;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface
     */
    protected DynamicEntityWriterInterface $dynamicEntityWriter;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    protected DynamicEntityValidatorInterface $dynamicEntityUpdateValidator;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface
     */
    protected DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityCollectionRequestBuilderInterface
     */
    protected DynamicEntityCollectionRequestBuilderInterface $dynamicEntityCollectionRequestBuilder;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface $dynamicEntityReader
     * @param \Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface $dynamicEntityWriter
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface $dynamicEntityUpdateValidator
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator
     * @param \Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityCollectionRequestBuilderInterface $dynamicEntityCollectionRequestBuilder
     */
    public function __construct(
        DynamicEntityReaderInterface $dynamicEntityReader,
        DynamicEntityWriterInterface $dynamicEntityWriter,
        DynamicEntityValidatorInterface $dynamicEntityUpdateValidator,
        DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator,
        DynamicEntityCollectionRequestBuilderInterface $dynamicEntityCollectionRequestBuilder
    ) {
        $this->dynamicEntityReader = $dynamicEntityReader;
        $this->dynamicEntityWriter = $dynamicEntityWriter;
        $this->dynamicEntityUpdateValidator = $dynamicEntityUpdateValidator;
        $this->dynamicEntityConfigurationTreeValidator = $dynamicEntityConfigurationTreeValidator;
        $this->dynamicEntityCollectionRequestBuilder = $dynamicEntityCollectionRequestBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityConfigurationNotFoundException
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function update(DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer): DynamicEntityCollectionResponseTransfer
    {
        if ($this->isRequestHasRelations($dynamicEntityCollectionRequestTransfer)) {
            return $this->updateWithRelations($dynamicEntityCollectionRequestTransfer);
        }

        $dynamicEntityConfigurationTransfer = $this->dynamicEntityReader->findDynamicEntityConfigurationByTableAlias(
            $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
        );

        if ($dynamicEntityConfigurationTransfer === null) {
            throw new DynamicEntityConfigurationNotFoundException();
        }

        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityUpdateValidator->validate(
            $dynamicEntityCollectionRequestTransfer,
            $dynamicEntityConfigurationTransfer,
            new DynamicEntityCollectionResponseTransfer(),
        );

        if ($dynamicEntityCollectionResponseTransfer->getErrors()->count()) {
            return $dynamicEntityCollectionResponseTransfer;
        }

        return $this->dynamicEntityWriter->executeUpdateTransaction($dynamicEntityCollectionRequestTransfer, $dynamicEntityConfigurationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function updateWithRelations(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityCriteriaTransfer = $this->createDynamicEntityCriteriaTransferFromCollectionRequest($dynamicEntityCollectionRequestTransfer);

        $dynamicEntityConfigurationCollectionTransfer = $this->dynamicEntityReader->getDynamicEntityConfigurationByDynamicEntityCollectionRequest(
            $dynamicEntityCollectionRequestTransfer,
        );

        $errorTransfer = $this->dynamicEntityConfigurationTreeValidator->validateDynamicEntityConfigurationCollection(
            $dynamicEntityConfigurationCollectionTransfer,
            $dynamicEntityCriteriaTransfer,
        );

        if ($errorTransfer !== null) {
            return (new DynamicEntityCollectionResponseTransfer())
                ->addError($errorTransfer);
        }

        $dynamicEntityCollectionRequestTransfers = $this->dynamicEntityCollectionRequestBuilder
            ->buildDynamicEntityCollectionRequestTransfersArrayIndexedByTableAlias(
                $dynamicEntityCollectionRequestTransfer,
                $dynamicEntityConfigurationCollectionTransfer,
            );

        $dynamicEntityCollectionResponseTransfer = $this->executeUpdateMultipleTransaction($dynamicEntityCollectionRequestTransfers, $dynamicEntityConfigurationCollectionTransfer);
        if ($dynamicEntityCollectionResponseTransfer->getErrors()->count() > 0) {
            return $dynamicEntityCollectionResponseTransfer;
        }

        return $this->getDynamicEnitiesByUpdatedRecords($dynamicEntityCollectionResponseTransfer, $dynamicEntityCriteriaTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer> $dynamicEntityCollectionRequestTransfers
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function executeUpdateMultipleTransaction(
        array $dynamicEntityCollectionRequestTransfers,
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $this->dynamicEntityWriter->startTransaction();

        $dynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();
        foreach ($dynamicEntityCollectionRequestTransfers as $tableAlias => $dynamicEntityCollectionRequestTransfer) {
            $dynamicEntityConfigurationTransfer = $this->findDynamicEntityConfigurationTransferByTableAlias($dynamicEntityConfigurationCollectionTransfer, $tableAlias);

            if ($dynamicEntityConfigurationTransfer === null) {
                return (new DynamicEntityCollectionResponseTransfer())
                    ->addError(
                        (new ErrorTransfer())
                            ->setMessage(static::ERROR_MESSAGE_CONFIGURATION_NOT_FOUND)
                            ->setParameters([
                                static::PLACEHOLDER_ALIAS_NAME => $tableAlias,
                            ]),
                    );
            }

            $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityUpdateValidator->validate(
                $dynamicEntityCollectionRequestTransfer,
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityCollectionResponseTransfer,
            );

            if ($dynamicEntityCollectionResponseTransfer->getErrors()->count()) {
                return $dynamicEntityCollectionResponseTransfer;
            }

            $dynamicEntityCollectionResponseTransfer = $this->mergeResponses(
                $dynamicEntityCollectionResponseTransfer,
                $this->dynamicEntityWriter->executeUpdateWithoutTransaction($dynamicEntityCollectionRequestTransfer, $dynamicEntityConfigurationTransfer),
                $dynamicEntityConfigurationTransfer,
            );
        }

        $this->dynamicEntityWriter->endTransaction($dynamicEntityCollectionResponseTransfer);

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $parentDynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $childDynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function mergeResponses(
        DynamicEntityCollectionResponseTransfer $parentDynamicEntityCollectionResponseTransfer,
        DynamicEntityCollectionResponseTransfer $childDynamicEntityCollectionResponseTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityCollectionResponseTransfer {
        if ($childDynamicEntityCollectionResponseTransfer->getErrors()->count() > 0) {
            return $childDynamicEntityCollectionResponseTransfer;
        }

        if (!$parentDynamicEntityCollectionResponseTransfer->getDynamicEntities()->count()) {
            return $childDynamicEntityCollectionResponseTransfer;
        }

        return $parentDynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return bool
     */
    protected function isRequestHasRelations(DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer): bool
    {
        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            foreach ($dynamicEntityTransfer->getFields() as $field) {
                if (is_array($field) && $field !== []) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer
     */
    protected function createDynamicEntityCriteriaTransferFromCollectionRequest(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityCriteriaTransfer {
        $dynamicEntityCriteriaTransfer = new DynamicEntityCriteriaTransfer();

        $relationChains = $this->dynamicEntityCollectionRequestBuilder
            ->buildRelationChainsFromDynamicEntityCollectionRequest($dynamicEntityCollectionRequestTransfer);
        $dynamicEntityCriteriaTransfer->setRelationChains($relationChains);

        $dynamicEntityConditionsTransfer = (new DynamicEntityConditionsTransfer())
            ->setTableAlias($dynamicEntityCollectionRequestTransfer->getTableAliasOrFail());
        $dynamicEntityCriteriaTransfer->setDynamicEntityConditions($dynamicEntityConditionsTransfer);

        return $dynamicEntityCriteriaTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     * @param string $tableAlias
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer|null
     */
    protected function findDynamicEntityConfigurationTransferByTableAlias(
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer,
        string $tableAlias
    ): ?DynamicEntityConfigurationTransfer {
        foreach ($dynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations() as $dynamicEntityConfigurationTransfer) {
            if ($dynamicEntityConfigurationTransfer->getTableAliasOrFail() === $tableAlias) {
                return $dynamicEntityConfigurationTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function getDynamicEnitiesByUpdatedRecords(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityConditionsTransfer = $dynamicEntityCriteriaTransfer->getDynamicEntityConditionsOrFail();
        foreach ($dynamicEntityCollectionResponseTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $dynamicEntityConditionsTransfer->addFieldCondition(
                (new DynamicEntityFieldConditionTransfer())
                    ->setName(static::FIELD_IDENTIFIER)
                    ->setValue($dynamicEntityTransfer->getIdentifier()),
            );
        }

        $dynamicEntityCollectionTransfer = $this->dynamicEntityReader->getEntityCollection($dynamicEntityCriteriaTransfer);
        $dynamicEntityCollectionResponseTransfer->setDynamicEntities(
            $dynamicEntityCollectionTransfer->getDynamicEntities(),
        );

        return $dynamicEntityCollectionResponseTransfer;
    }
}
