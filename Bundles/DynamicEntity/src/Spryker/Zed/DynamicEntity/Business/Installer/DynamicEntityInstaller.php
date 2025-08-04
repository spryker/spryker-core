<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Installer;

use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityConfiguration\DynamicEntityConfigurationColumnDetailProviderInterface;
use Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityFileNotReadableException;
use Spryker\Zed\DynamicEntity\Business\Installer\Validator\FieldMappingValidatorInterface;
use Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface;
use Spryker\Zed\DynamicEntity\DynamicEntityConfig;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DynamicEntityInstaller implements DynamicEntityInstallerInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const TABLE_ALIAS = 'tableAlias';

    /**
     * @var string
     */
    protected const TABLE_NAME = 'tableName';

    /**
     * @var string
     */
    protected const MESSAGE_FILE_NOT_READABLE = 'Could not read from file: %s';

    /**
     * @var string
     */
    protected const MESSAGE_FILE_CONTAINS_INVALID_JSON = 'File contains invalid JSON: %s';

    /**
     * @param \Spryker\Zed\DynamicEntity\DynamicEntityConfig $dynamicEntityConfig
     * @param \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface $dynamicEntityRepository
     * @param \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface $entityManager
     * @param \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface $dynamicEntityMapper
     * @param \Spryker\Zed\DynamicEntity\Business\Installer\Validator\FieldMappingValidatorInterface $fieldMappingValidator
     * @param \Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityConfiguration\DynamicEntityConfigurationColumnDetailProviderInterface $dynamicEntityConfigurationColumnDetailProvider
     */
    public function __construct(
        protected DynamicEntityConfig $dynamicEntityConfig,
        protected DynamicEntityRepositoryInterface $dynamicEntityRepository,
        protected DynamicEntityEntityManagerInterface $entityManager,
        protected DynamicEntityMapperInterface $dynamicEntityMapper,
        protected FieldMappingValidatorInterface $fieldMappingValidator,
        protected DynamicEntityConfigurationColumnDetailProviderInterface $dynamicEntityConfigurationColumnDetailProvider
    ) {
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->getTransactionHandler()->handleTransaction(function (): void {
            $this->executeTransaction();
        });
    }

    /**
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityFileNotReadableException
     *
     * @return void
     */
    protected function executeTransaction(): void
    {
        $installerConfigurationDataPath = $this->dynamicEntityConfig->getInstallerConfigurationDataFilePath();
        $installerConfigurationData = file_get_contents($installerConfigurationDataPath);

        if ($installerConfigurationData === false) {
            throw new DynamicEntityFileNotReadableException(sprintf(static::MESSAGE_FILE_NOT_READABLE, $installerConfigurationDataPath));
        }

        $installerConfigurationDecodedData = json_decode($installerConfigurationData, true);

        if ($installerConfigurationDecodedData === false) {
            throw new DynamicEntityFileNotReadableException(sprintf(static::MESSAGE_FILE_CONTAINS_INVALID_JSON, $installerConfigurationData));
        }

        $tableAliases = $this->getTableAliases();
        $dynamicEntityConfigurationCollectionTransfer = new DynamicEntityConfigurationCollectionTransfer();
        $dynamicEntityConfigurationTransfers = [];

        foreach ($installerConfigurationDecodedData as $dynamicEntityConfiguration) {
            if (!isset($dynamicEntityConfiguration[static::TABLE_ALIAS])) {
                continue;
            }

            if (
                !array_key_exists($dynamicEntityConfiguration[static::TABLE_ALIAS], $tableAliases)
                && !in_array($dynamicEntityConfiguration[static::TABLE_NAME], $tableAliases)
            ) {
                $dynamicEntityConfigurationTransfer = $this->dynamicEntityMapper->mapDynamicEntityConfigurationToDynamicEntityConfigurationTransfer(
                    $dynamicEntityConfiguration,
                    new DynamicEntityConfigurationTransfer(),
                );
                $dynamicEntityConfigurationCollectionTransfer->addDynamicEntityConfiguration($dynamicEntityConfigurationTransfer);
            }
        }

        $dynamicEntityConfigurationCollectionTransfer = $this->dynamicEntityConfigurationColumnDetailProvider->provideColumDetails($dynamicEntityConfigurationCollectionTransfer);

        foreach ($dynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations() as $dynamicEntityConfigurationTransfer) {
            $dynamicEntityConfigurationTransfer = $this->entityManager->createDynamicEntityConfiguration($dynamicEntityConfigurationTransfer);
            $dynamicEntityConfigurationTransfers[$dynamicEntityConfigurationTransfer->getTableAliasOrFail()] = $dynamicEntityConfigurationTransfer;
        }

        $this->createChildRelations($dynamicEntityConfigurationCollectionTransfer, $dynamicEntityConfigurationTransfers);
    }

    /**
     * @return array<string, string>
     */
    protected function getTableAliases(): array
    {
        $dynamicEntityConfigurationCollectionTransfer = $this->dynamicEntityRepository->getDynamicEntityConfigurationCollection(new DynamicEntityConfigurationCriteriaTransfer());
        $tableAliases = [];

        foreach ($dynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations() as $dynamicEntityConfiguration) {
            $tableAliases[$dynamicEntityConfiguration->getTableAliasOrFail()] = $dynamicEntityConfiguration->getTableNameOrFail();
        }

        return $tableAliases;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer
     * @param array<\Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $dynamicEntityConfigurationTransfers
     *
     * @return void
     */
    protected function createChildRelations(
        DynamicEntityConfigurationCollectionTransfer $dynamicEntityConfigurationCollectionTransfer,
        array $dynamicEntityConfigurationTransfers
    ): void {
        foreach ($dynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations() as $dynamicEntityConfigurationTransfer) {
            $indexedChildRelations = $this->getChildRelationsIndexedByTableAlias($dynamicEntityConfigurationTransfer);

            if ($indexedChildRelations === []) {
                continue;
            }

            if (array_key_exists($dynamicEntityConfigurationTransfer->getTableAliasOrFail(), $dynamicEntityConfigurationTransfers) === false) {
                continue;
            }

            $parentDynamicEntityConfigurationTransfer = $dynamicEntityConfigurationTransfers[$dynamicEntityConfigurationTransfer->getTableAliasOrFail()];

            $childDynamicEntityConfigurationCollectionTransfer = $this->dynamicEntityRepository->getDynamicEntityConfigurationCollectionByTableAliasesOrTableNames(
                [],
                array_keys($indexedChildRelations),
            );

            $this->fieldMappingValidator->validate($childDynamicEntityConfigurationCollectionTransfer, $parentDynamicEntityConfigurationTransfer, $indexedChildRelations);
            $this->entityManager->createDynamicEntityConfigurationRelation($childDynamicEntityConfigurationCollectionTransfer, $parentDynamicEntityConfigurationTransfer, $indexedChildRelations);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return array<string, mixed>
     */
    protected function getChildRelationsIndexedByTableAlias(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): array
    {
        $childRelationsIndexedByTableName = [];

        foreach ($dynamicEntityConfigurationTransfer->getChildRelations() as $childRelationTransfer) {
            $childRelationsIndexedByTableName[$childRelationTransfer->getChildDynamicEntityConfigurationOrFail()->getTableAliasOrFail()] = $childRelationTransfer->toArray();
        }

        return $childRelationsIndexedByTableName;
    }
}
