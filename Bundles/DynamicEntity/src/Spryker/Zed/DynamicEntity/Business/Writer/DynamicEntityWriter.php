<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Writer;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityRelationFieldMappingTransfer;
use Generated\Shared\Transfer\DynamicEntityRelationTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Filter\DynamicEntityFilterInterface;
use Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface;
use Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface;
use Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityComprehensiveValidatorInterface;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface;

class DynamicEntityWriter implements DynamicEntityWriterInterface
{
    /**
     * @var int
     */
    protected int $indexCounter = 0;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_MISSING_IDENTIFIER = 'dynamic_entity.validation.missing_identifier';

    /**
     * @var \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface
     */
    protected DynamicEntityEntityManagerInterface $entityManager;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityComprehensiveValidatorInterface
     */
    protected DynamicEntityComprehensiveValidatorInterface $validator;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Filter\DynamicEntityFilterInterface
     */
    protected DynamicEntityFilterInterface $filter;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface
     */
    protected DynamicEntityIndexerInterface $indexer;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface
     */
    protected DynamicEntityMapperInterface $mapper;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface
     */
    protected DynamicEntityErrorPathResolverInterface $errorPathResolver;

    /**
     * @param \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface $entityManager
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityComprehensiveValidatorInterface $validator
     * @param \Spryker\Zed\DynamicEntity\Business\Filter\DynamicEntityFilterInterface $filter
     * @param \Spryker\Zed\DynamicEntity\Business\Indexer\DynamicEntityIndexerInterface $indexer
     * @param \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface $mapper
     * @param \Spryker\Zed\DynamicEntity\Business\Resolver\DynamicEntityErrorPathResolverInterface $errorPathResolver
     */
    public function __construct(
        DynamicEntityEntityManagerInterface $entityManager,
        DynamicEntityComprehensiveValidatorInterface $validator,
        DynamicEntityFilterInterface $filter,
        DynamicEntityIndexerInterface $indexer,
        DynamicEntityMapperInterface $mapper,
        DynamicEntityErrorPathResolverInterface $errorPathResolver
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->filter = $filter;
        $this->indexer = $indexer;
        $this->mapper = $mapper;
        $this->errorPathResolver = $errorPathResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function createDynamicEntity(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $index = $this->indexCounter;
        $this->indexCounter++;

        $errorTransfers = $this->validator->validateDynamicEntity(
            $dynamicEntityCollectionRequestTransfer,
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $index,
        );
        if ($errorTransfers !== []) {
            return $this->mergeErrors($dynamicEntityCollectionResponseTransfer, $errorTransfers);
        }

        $errorPath = $this->errorPathResolver->getErrorPath($index, $dynamicEntityConfigurationTransfer->getTableAliasOrFail());
        $dynamicEntityTransfer = $this->filter->filter($dynamicEntityConfigurationTransfer, $dynamicEntityTransfer);

        $parentDynamicEntityCollectionResponseTransfer = $this->entityManager->createDynamicEntity(
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $errorPath,
        );

        if ($parentDynamicEntityCollectionResponseTransfer->getErrors()->count() > 0) {
            return $this->mergeErrors(
                $dynamicEntityCollectionResponseTransfer,
                $parentDynamicEntityCollectionResponseTransfer->getErrors()->getArrayCopy(),
            );
        }

        $dynamicEntityTransfer = $parentDynamicEntityCollectionResponseTransfer->getDynamicEntities()->offsetGet(0);
        $childDynamicEntityConfigurationsIndexedByRelationName = $this->indexer->getChildRelationsIndexedByRelationName($dynamicEntityConfigurationTransfer);
        $childDynamicEntityCollectionResponseTransfer = $this->createChildDynamicEntities(
            $dynamicEntityTransfer,
            new DynamicEntityCollectionResponseTransfer(),
            $childDynamicEntityConfigurationsIndexedByRelationName,
            $errorPath,
        );

        if ($childDynamicEntityCollectionResponseTransfer->getErrors()->count() > 0) {
            return $this->mergeErrors(
                $dynamicEntityCollectionResponseTransfer,
                $childDynamicEntityCollectionResponseTransfer->getErrors()->getArrayCopy(),
            );
        }

        $dynamicEntityCollectionResponseTransfer->addDynamicEntity($dynamicEntityTransfer);

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function updateDynamicEntity(
        DynamicEntityTransfer $dynamicEntityTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $index = $this->indexCounter;
        $this->indexCounter++;

        $errorTransfers = $this->validator->validateDynamicEntity(
            $dynamicEntityCollectionRequestTransfer,
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $index,
        );
        if ($errorTransfers !== []) {
            return $this->mergeErrors($dynamicEntityCollectionResponseTransfer, $errorTransfers);
        }

        $errorPath = $this->errorPathResolver->getErrorPath($index, $dynamicEntityConfigurationTransfer->getTableAliasOrFail());
        $dynamicEntityTransfer = $this->filter->filter($dynamicEntityConfigurationTransfer, $dynamicEntityTransfer);

        $dynamicEntityConditionsTransfer = $this->mapper->mapDynamicEntityTransferToDynamicEntityConditionsTransfer(
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
        );

        if ($dynamicEntityConditionsTransfer === null && !$dynamicEntityCollectionRequestTransfer->getIsCreatable()) {
            return $this->addMissingIdendifierErrorToResponseTransfer(
                $dynamicEntityCollectionResponseTransfer,
                $dynamicEntityConfigurationTransfer,
                $errorPath,
            );
        }

        if ($dynamicEntityConditionsTransfer === null) {
            $dynamicEntityConditionsTransfer = new DynamicEntityConditionsTransfer();
        }

        $parentDynamicEntityCollectionResponseTransfer = $this->entityManager->updateDynamicEntity(
            $dynamicEntityTransfer,
            $dynamicEntityConfigurationTransfer,
            $dynamicEntityConditionsTransfer,
            $dynamicEntityCollectionRequestTransfer,
            $errorPath,
        );

        if ($parentDynamicEntityCollectionResponseTransfer->getErrors()->count() > 0) {
            return $this->mergeErrors(
                $dynamicEntityCollectionResponseTransfer,
                $parentDynamicEntityCollectionResponseTransfer->getErrors()->getArrayCopy(),
            );
        }

        $dynamicEntityTransfer = $parentDynamicEntityCollectionResponseTransfer->getDynamicEntities()->offsetGet(0);
        $childDynamicEntityConfigurationsIndexedByRelationName = $this->indexer->getChildRelationsIndexedByRelationName($dynamicEntityConfigurationTransfer);
        $childDynamicEntityCollectionResponseTransfer = $this->updateChildDynamicEntities(
            $dynamicEntityTransfer,
            $dynamicEntityCollectionRequestTransfer,
            $dynamicEntityCollectionResponseTransfer,
            $childDynamicEntityConfigurationsIndexedByRelationName,
            $errorPath,
        );

        if ($childDynamicEntityCollectionResponseTransfer->getErrors()->count() > 0) {
            return $this->mergeErrors(
                $dynamicEntityCollectionResponseTransfer,
                $childDynamicEntityCollectionResponseTransfer->getErrors()->getArrayCopy(),
            );
        }

        $dynamicEntityCollectionResponseTransfer->addDynamicEntity($dynamicEntityTransfer);

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $parentDynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $childDynamicEntityConfigurationsIndexedByRelationName
     * @param string $parentErrorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function createChildDynamicEntities(
        DynamicEntityTransfer $parentDynamicEntityTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $childDynamicEntityConfigurationsIndexedByRelationName,
        string $parentErrorPath
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($parentDynamicEntityTransfer->getChildRelations() as $dynamicEntityRelationTransfer) {
            $dynamicEntityCollectionResponseTransfer = $this->createChildDynamicEntitiesFromRelation(
                $dynamicEntityRelationTransfer,
                $parentDynamicEntityTransfer,
                $dynamicEntityCollectionResponseTransfer,
                $childDynamicEntityConfigurationsIndexedByRelationName,
                $parentErrorPath,
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityRelationTransfer $dynamicEntityRelationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $parentDynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $childDynamicEntityConfigurationsIndexedByRelationName
     * @param string $parentErrorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function createChildDynamicEntitiesFromRelation(
        DynamicEntityRelationTransfer $dynamicEntityRelationTransfer,
        DynamicEntityTransfer $parentDynamicEntityTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $childDynamicEntityConfigurationsIndexedByRelationName,
        string $parentErrorPath
    ): DynamicEntityCollectionResponseTransfer {
        $childDynamicEntityConfiguration = $childDynamicEntityConfigurationsIndexedByRelationName[$dynamicEntityRelationTransfer->getNameOrFail()];
        foreach ($dynamicEntityRelationTransfer->getDynamicEntities() as $childIndex => $childDynamicEntityTransfer) {
            $childErrorPath = $this->errorPathResolver->getErrorPath($childIndex, $childDynamicEntityConfiguration->getChildDynamicEntityConfigurationOrFail()->getTableAliasOrFail(), $parentErrorPath);

            $childDynamicEntityTransfer = $this->filter->filter($childDynamicEntityConfiguration->getChildDynamicEntityConfigurationOrFail(), $childDynamicEntityTransfer);
            $childDynamicEntityTransfer = $this->addParentForeignKeyToChildFields(
                $parentDynamicEntityTransfer,
                $childDynamicEntityTransfer,
                $childDynamicEntityConfiguration->getRelationFieldMappings()->offsetGet(0),
            );

            $dynamicEntityCollectionResponseTransfer = $this->entityManager->createChildDynamicEntity(
                $childDynamicEntityTransfer,
                $childDynamicEntityConfiguration,
                $childErrorPath,
            );

            if ($childDynamicEntityTransfer->getChildRelations()->count() > 0) {
                $dynamicEntityCollectionResponseTransfer = $this->createChildDynamicEntities(
                    $childDynamicEntityTransfer,
                    $dynamicEntityCollectionResponseTransfer,
                    $childDynamicEntityConfigurationsIndexedByRelationName,
                    $childErrorPath,
                );
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $parentDynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $childDynamicEntityConfigurationsIndexedByRelationName
     * @param string $parentErrorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function updateChildDynamicEntities(
        DynamicEntityTransfer $parentDynamicEntityTransfer,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $childDynamicEntityConfigurationsIndexedByRelationName,
        string $parentErrorPath
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($parentDynamicEntityTransfer->getChildRelations() as $dynamicEntityRelationTransfer) {
            $dynamicEntityCollectionResponseTransfer = $this->updateChildDynamicEntititesFromRelation(
                $dynamicEntityRelationTransfer,
                $parentDynamicEntityTransfer,
                $dynamicEntityCollectionRequestTransfer,
                $dynamicEntityCollectionResponseTransfer,
                $childDynamicEntityConfigurationsIndexedByRelationName,
                $parentErrorPath,
            );
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityRelationTransfer $dynamicEntityRelationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $parentDynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param array<string, \Generated\Shared\Transfer\DynamicEntityConfigurationRelationTransfer> $childDynamicEntityConfigurationsIndexedByRelationName
     * @param string $parentErrorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function updateChildDynamicEntititesFromRelation(
        DynamicEntityRelationTransfer $dynamicEntityRelationTransfer,
        DynamicEntityTransfer $parentDynamicEntityTransfer,
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $childDynamicEntityConfigurationsIndexedByRelationName,
        string $parentErrorPath
    ): DynamicEntityCollectionResponseTransfer {
        $childDynamicEntityConfiguration = $childDynamicEntityConfigurationsIndexedByRelationName[$dynamicEntityRelationTransfer->getNameOrFail()];
        foreach ($dynamicEntityRelationTransfer->getDynamicEntities() as $childIndex => $childDynamicEntityTransfer) {
            $childDynamicEntityConfigurationTransfer = $childDynamicEntityConfiguration->getChildDynamicEntityConfigurationOrFail();
            $childErrorPath = $this->errorPathResolver->getErrorPath(
                $childIndex,
                $childDynamicEntityConfigurationTransfer->getTableAliasOrFail(),
                $parentErrorPath,
            );

            $childDynamicEntityTransfer = $this->filter->filter($childDynamicEntityConfigurationTransfer, $childDynamicEntityTransfer);
            $childDynamicEntityTransfer = $this->addParentForeignKeyToChildFields(
                $parentDynamicEntityTransfer,
                $childDynamicEntityTransfer,
                $childDynamicEntityConfiguration->getRelationFieldMappings()->offsetGet(0),
            );

            $dynamicEntityConditionsTransfer = $this->mapper->mapDynamicEntityTransferToDynamicEntityConditionsTransfer(
                $childDynamicEntityTransfer,
                $childDynamicEntityConfigurationTransfer,
            );

            if ($dynamicEntityConditionsTransfer === null && !$dynamicEntityCollectionRequestTransfer->getIsCreatable()) {
                $this->addMissingIdendifierErrorToResponseTransfer(
                    $dynamicEntityCollectionResponseTransfer,
                    $childDynamicEntityConfigurationTransfer,
                    $childErrorPath,
                );

                continue;
            }

            if ($dynamicEntityConditionsTransfer === null) {
                $dynamicEntityConditionsTransfer = new DynamicEntityConditionsTransfer();
            }

            $childDynamicEntityCollectionResponseTransfer = $this->entityManager->updateChildDynamicEntity(
                $childDynamicEntityTransfer,
                $childDynamicEntityConfiguration,
                $dynamicEntityConditionsTransfer,
                $dynamicEntityCollectionRequestTransfer,
                $childErrorPath,
            );

            if ($childDynamicEntityCollectionResponseTransfer->getErrors()->count() > 0) {
                $dynamicEntityCollectionResponseTransfer = $this->mergeErrors(
                    $dynamicEntityCollectionResponseTransfer,
                    $childDynamicEntityCollectionResponseTransfer->getErrors()->getArrayCopy(),
                );
            }

            if ($childDynamicEntityTransfer->getChildRelations()->count() > 0) {
                $dynamicEntityCollectionResponseTransfer = $this->updateChildDynamicEntities(
                    $childDynamicEntityTransfer,
                    $dynamicEntityCollectionRequestTransfer,
                    $dynamicEntityCollectionResponseTransfer,
                    $childDynamicEntityConfigurationsIndexedByRelationName,
                    $childErrorPath,
                );
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $parentDynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $childDynamicEntityTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityRelationFieldMappingTransfer $dynamicEntityRelationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityTransfer
     */
    protected function addParentForeignKeyToChildFields(
        DynamicEntityTransfer $parentDynamicEntityTransfer,
        DynamicEntityTransfer $childDynamicEntityTransfer,
        DynamicEntityRelationFieldMappingTransfer $dynamicEntityRelationTransfer
    ): DynamicEntityTransfer {
        $parentForeignKeyField = [
            $dynamicEntityRelationTransfer->getChildFieldName() => $parentDynamicEntityTransfer->getIdentifierOrFail(),
        ];

        return $childDynamicEntityTransfer->setFields(
            array_merge($childDynamicEntityTransfer->getFields(), $parentForeignKeyField),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param array<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function mergeErrors(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        array $errorTransfers = []
    ): DynamicEntityCollectionResponseTransfer {
        if ($errorTransfers === []) {
            return $dynamicEntityCollectionResponseTransfer;
        }

        foreach ($errorTransfers as $errorTransfer) {
            $dynamicEntityCollectionResponseTransfer->addError($errorTransfer);
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param string $errorPath
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function addMissingIdendifierErrorToResponseTransfer(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        string $errorPath
    ): DynamicEntityCollectionResponseTransfer {
        $errorMessageTransfer = (new ErrorTransfer())
            ->setEntityIdentifier($dynamicEntityConfigurationTransfer->getTableAliasOrFail())
            ->setMessage(static::GLOSSARY_KEY_ERROR_MISSING_IDENTIFIER)
            ->setParameters([DynamicEntityConfig::ERROR_PATH => $errorPath]);

        return $dynamicEntityCollectionResponseTransfer->addError($errorMessageTransfer);
    }
}
