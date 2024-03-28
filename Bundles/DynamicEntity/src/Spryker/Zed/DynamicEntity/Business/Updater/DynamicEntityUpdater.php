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
use Generated\Shared\Transfer\DynamicEntityRelationTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityCollectionRequestBuilderInterface;
use Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityConfigurationNotFoundException;
use Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface;
use Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface;
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
     * @var \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface
     */
    protected DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface $dynamicEntityReader
     * @param \Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface $dynamicEntityWriter
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface $dynamicEntityUpdateValidator
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator
     * @param \Spryker\Zed\DynamicEntity\Business\Builder\DynamicEntityCollectionRequestBuilderInterface $dynamicEntityCollectionRequestBuilder
     * @param \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver
     */
    public function __construct(
        DynamicEntityReaderInterface $dynamicEntityReader,
        DynamicEntityWriterInterface $dynamicEntityWriter,
        DynamicEntityValidatorInterface $dynamicEntityUpdateValidator,
        DynamicEntityConfigurationTreeValidatorInterface $dynamicEntityConfigurationTreeValidator,
        DynamicEntityCollectionRequestBuilderInterface $dynamicEntityCollectionRequestBuilder,
        DynamicEntityErrorPathResolverInterface $dynamicEntityErrorPathResolver
    ) {
        $this->dynamicEntityReader = $dynamicEntityReader;
        $this->dynamicEntityWriter = $dynamicEntityWriter;
        $this->dynamicEntityUpdateValidator = $dynamicEntityUpdateValidator;
        $this->dynamicEntityConfigurationTreeValidator = $dynamicEntityConfigurationTreeValidator;
        $this->dynamicEntityCollectionRequestBuilder = $dynamicEntityCollectionRequestBuilder;
        $this->dynamicEntityErrorPathResolver = $dynamicEntityErrorPathResolver;
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

        $dynamicEntityCollectionRequestTreeBranches = $this->dynamicEntityCollectionRequestBuilder
            ->buildDynamicEntityCollectionRequestTreeBranches(
                $dynamicEntityCollectionRequestTransfer,
                $dynamicEntityConfigurationCollectionTransfer,
            );

        $dynamicEntityCollectionResponseTransfer = $this->executeUpdateMultipleTransaction($dynamicEntityCollectionRequestTreeBranches, $dynamicEntityConfigurationCollectionTransfer);
        if ($dynamicEntityCollectionResponseTransfer->getErrors()->count() > 0) {
            return $dynamicEntityCollectionResponseTransfer;
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param array<\Spryker\Zed\DynamicEntity\Business\Request\DynamicEntityCollectionRequestTreeBranchInterface> $dynamicEntityCollectionRequestTreeBranches
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function executeUpdateMultipleTransaction(
        array $dynamicEntityCollectionRequestTreeBranches,
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityRelationsIndexedByTableAliases = $this->indexDynamicEntityRelationsByTableAliases($dynamicEntityConfigurationCollectionTransfer);
        $dynamicEntityRelationsIndexedByChildTableAliases = [];

        $this->dynamicEntityWriter->startTransaction();

        $dynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();
        foreach ($dynamicEntityCollectionRequestTreeBranches as $index => $dynamicEntityCollectionRequestTreeBranch /*$dynamicEntities*/) {
            $dynamicEntityCollectionRequestTransfer = $dynamicEntityCollectionRequestTreeBranch->getParentCollectionRequestTransfer();
            $currentTableAlias = $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail();

            /** @var array<string, string> $dynamicEntityRelationsIndexedByChildTableAliases */
            $dynamicEntityRelationsIndexedByChildTableAliases = $dynamicEntityRelationsIndexedByTableAliases[$currentTableAlias];
            $errorPath = $this->dynamicEntityErrorPathResolver->getErrorPath($index, $currentTableAlias);

            $parentDynamicEntityCollectionResponseTransfer = $this->processDynamicEntities(
                $dynamicEntityConfigurationCollectionTransfer,
                $dynamicEntityCollectionRequestTransfer,
                $errorPath,
            );

            if ($parentDynamicEntityCollectionResponseTransfer->getErrors()->count() > 0) {
                foreach ($parentDynamicEntityCollectionResponseTransfer->getErrors() as $errorTransfer) {
                    $dynamicEntityCollectionResponseTransfer->addError($errorTransfer);
                }

                continue;
            }

            $dynamicEntityCollectionResponseTransfer->addDynamicEntity($parentDynamicEntityCollectionResponseTransfer->getDynamicEntities()[0]);

            if ($dynamicEntityCollectionRequestTreeBranch->getChildCollectionRequestTransfers() !== null) {
                $dynamicEntityCollectionResponseTransfer = $this->updateChildDynamicEntities(
                    $dynamicEntityCollectionRequestTreeBranch->getChildCollectionRequestTransfers(),
                    $dynamicEntityConfigurationCollectionTransfer,
                    $dynamicEntityCollectionResponseTransfer,
                    $dynamicEntityRelationsIndexedByChildTableAliases,
                    $errorPath,
                );

                continue;
            }
        }

        $this->dynamicEntityWriter->endTransaction($dynamicEntityCollectionResponseTransfer);

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer> $dynamicEntityCollectionRequestTransfers
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $parentDynamicEntityCollectionResponseTransfer
     * @param array<string, string> $dynamicEntityRelationsIndexedByTableAliases
     * @param string|null $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function updateChildDynamicEntities(
        array $dynamicEntityCollectionRequestTransfers,
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer,
        DynamicEntityCollectionResponseTransfer $parentDynamicEntityCollectionResponseTransfer,
        array $dynamicEntityRelationsIndexedByTableAliases,
        ?string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityCollectionRequestTransfers as $dynamicEntityCollectionRequestTransfer) {
            $childDynamicEntityCollectionResponseTransfer = $this->processDynamicEntities(
                $dynamicEntityConfigurationCollectionTransfer,
                $dynamicEntityCollectionRequestTransfer,
                $errorPath,
            );

            $parentDynamicEntityCollectionResponseTransfer = $this->mergeResponses(
                $parentDynamicEntityCollectionResponseTransfer,
                $childDynamicEntityCollectionResponseTransfer,
                $dynamicEntityRelationsIndexedByTableAliases[$dynamicEntityCollectionRequestTransfer->getTableAliasOrFail()],
            );
        }

        return $parentDynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param string|null $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function processDynamicEntities(
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        ?string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityConfigurationTransfer = $this->findDynamicEntityConfigurationTransferByTableAlias(
            $dynamicEntityConfigurationCollectionTransfer,
            $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
        );

        if ($dynamicEntityConfigurationTransfer === null) {
            return (new DynamicEntityCollectionResponseTransfer())
                ->addError(
                    (new ErrorTransfer())
                        ->setMessage(static::ERROR_MESSAGE_CONFIGURATION_NOT_FOUND)
                        ->setParameters([
                            static::PLACEHOLDER_ALIAS_NAME => $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
                        ]),
                );
        }

        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityUpdateValidator->validate(
            $dynamicEntityCollectionRequestTransfer,
            $dynamicEntityConfigurationTransfer,
            new DynamicEntityCollectionResponseTransfer(),
            $errorPath,
        );

        if ($dynamicEntityCollectionResponseTransfer->getErrors()->count()) {
            return $dynamicEntityCollectionResponseTransfer;
        }

        return $this->dynamicEntityWriter->executeUpdateWithoutTransaction(
            $dynamicEntityCollectionRequestTransfer,
            $dynamicEntityConfigurationTransfer,
            $errorPath,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $parentDynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $childDynamicEntityCollectionResponseTransfer
     * @param string $relationName
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function mergeResponses(
        DynamicEntityCollectionResponseTransfer $parentDynamicEntityCollectionResponseTransfer,
        DynamicEntityCollectionResponseTransfer $childDynamicEntityCollectionResponseTransfer,
        string $relationName
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($childDynamicEntityCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $parentDynamicEntityCollectionResponseTransfer->addError($errorTransfer);
        }

        if ($parentDynamicEntityCollectionResponseTransfer->getDynamicEntities()->count() === 0) {
            return $parentDynamicEntityCollectionResponseTransfer;
        }

        $parentDynamicEntityTransfer = $parentDynamicEntityCollectionResponseTransfer->getDynamicEntities()->offsetGet(
            $parentDynamicEntityCollectionResponseTransfer->getDynamicEntities()->count() - 1,
        );
        $parentDynamicEntityTransfer->addChildRelation(
            (new DynamicEntityRelationTransfer())
                ->setName($relationName)
                ->setDynamicEntities($childDynamicEntityCollectionResponseTransfer->getDynamicEntities()),
        );

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
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     *
     * @return array<string, array<string, string|null>>
     */
    protected function indexDynamicEntityRelationsByTableAliases(
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
    ): array {
        $dynamicEntityRelationsIndexedByTableAliases = [];

        foreach ($dynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations() as $dynamicEntityConfiguration) {
            $dynamicEntityRelationsIndexedByTableAliases = $this->addChildDynamicEntityRelationsIndexedByTableAliases(
                $dynamicEntityRelationsIndexedByTableAliases,
                $dynamicEntityConfiguration,
            );
        }

        return $dynamicEntityRelationsIndexedByTableAliases;
    }

    /**
     * @param array<string, array<string, string|null>> $dynamicEntityRelationsIndexedByTableAliases
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfiguration
     *
     * @return array<string, array<string, string|null>>
     */
    protected function addChildDynamicEntityRelationsIndexedByTableAliases(
        array $dynamicEntityRelationsIndexedByTableAliases,
        DynamicEntityConfigurationTransfer $dynamicEntityConfiguration
    ): array {
        foreach ($dynamicEntityConfiguration->getChildRelations() as $childRelation) {
            $parentTableAlias = $dynamicEntityConfiguration->getTableAliasOrFail();
            $childTableAlias = $childRelation->getChildDynamicEntityConfigurationOrFail()->getTableAliasOrFail();

            $dynamicEntityRelationsIndexedByTableAliases[$parentTableAlias][$childTableAlias] = $childRelation->getName();
        }

        return $dynamicEntityRelationsIndexedByTableAliases;
    }
}
