<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Validator\Rules\Configuration;

use ArrayObject;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\Rules\ValidatorRuleInterface;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface;

class UniqueTableNameAliasValidatorRule implements ValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_TABLE_NAME_TABLE_ALIAS = 'Table name or table alias is not unique for dynamic entity. Table: %s';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_TABLE_NAME = 'Table name is not unique for dynamic entity. Table: %s';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_TABLE_ALIAS = 'Table alias is not unique for dynamic entity. Table: %s';

    /**
     * @var string
     */
    protected const TYPE = 'type';

    /**
     * @var string
     */
    protected const TABLE_ALIAS = 'table_alias';

    /**
     * @var \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface
     */
    protected DynamicEntityRepositoryInterface $repository;

    /**
     * @param \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface $repository
     */
    public function __construct(DynamicEntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $dynamicEntityConfigurationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $dynamicEntityConfigurationTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        if ($dynamicEntityConfigurationTransfers->count() === 0) {
            return $errorCollectionTransfer;
        }

        $tableNames = [];
        $tableAliases = [];

        /** @var \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer */
        foreach ($dynamicEntityConfigurationTransfers as $dynamicEntityConfigurationTransfer) {
            $tableNames[] = $dynamicEntityConfigurationTransfer->getTableNameOrFail();
            $tableAliases[] = $dynamicEntityConfigurationTransfer->getTableAliasOrFail();
        }

        /** @var array<int, \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration> $existDynamicEntityConfigurations */
        $existDynamicEntityConfigurations = $this->repository->findDynamicEntityConfigurationByTableAliasesOrTableNames($tableNames, $tableAliases);

        if ($this->isCreateTransfers($dynamicEntityConfigurationTransfers)) {
            return $this->buildErrorCollectionTransfer($errorCollectionTransfer, $existDynamicEntityConfigurations);
        }

        return $this->validateUpdateTransfers($dynamicEntityConfigurationTransfers, $existDynamicEntityConfigurations);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $dynamicEntityConfigurationTransfers
     *
     * @return bool
     */
    protected function isCreateTransfers(ArrayObject $dynamicEntityConfigurationTransfers): bool
    {
        if ($dynamicEntityConfigurationTransfers->count() === 0) {
            return false;
        }

        return $dynamicEntityConfigurationTransfers->getArrayCopy()[0]->getIdDynamicEntityConfiguration() === null;
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param array<int, \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration> $existDynamicEntityConfigurations
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function buildErrorCollectionTransfer(
        ErrorCollectionTransfer $errorCollectionTransfer,
        array $existDynamicEntityConfigurations
    ): ErrorCollectionTransfer {
        /** @var \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration $existDynamicEntityConfiguration */
        foreach ($existDynamicEntityConfigurations as $existDynamicEntityConfiguration) {
            $errorCollectionTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(sprintf(static::ERROR_MESSAGE_TABLE_NAME_TABLE_ALIAS, $existDynamicEntityConfiguration->getTableName()))
                    ->setParameters([static::TYPE => static::TABLE_ALIAS])
                    ->setEntityIdentifier($existDynamicEntityConfiguration->getTableName()),
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $dynamicEntityConfigurationTransfers
     * @param array<int, \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration> $existingDynamicEntityConfigurations
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validateUpdateTransfers(
        ArrayObject $dynamicEntityConfigurationTransfers,
        array $existingDynamicEntityConfigurations
    ): ErrorCollectionTransfer {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        foreach ($dynamicEntityConfigurationTransfers as $dynamicEntityConfigurationTransfer) {
            $errorCollectionTransfer = $this->validateExistingDynamicEntityConfigurations($existingDynamicEntityConfigurations, $dynamicEntityConfigurationTransfer, $errorCollectionTransfer);
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param array<\Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration> $existingDynamicEntityConfigurations
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validateExistingDynamicEntityConfigurations(
        array $existingDynamicEntityConfigurations,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        /** @var \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration $existingDynamicEntityConfiguration */
        foreach ($existingDynamicEntityConfigurations as $existingDynamicEntityConfiguration) {
            if ($existingDynamicEntityConfiguration->getIdDynamicEntityConfiguration() === $dynamicEntityConfigurationTransfer->getIdDynamicEntityConfiguration()) {
                continue;
            }

            if ($existingDynamicEntityConfiguration->getTableName() === $dynamicEntityConfigurationTransfer->getTableName()) {
                $errorCollectionTransfer->addError(
                    (new ErrorTransfer())
                        ->setMessage(sprintf(static::ERROR_MESSAGE_TABLE_NAME, $existingDynamicEntityConfiguration->getTableName()))
                        ->setParameters([static::TYPE => static::TABLE_ALIAS])
                        ->setEntityIdentifier($existingDynamicEntityConfiguration->getTableName()),
                );
            }

            if ($existingDynamicEntityConfiguration->getTableAlias() === $dynamicEntityConfigurationTransfer->getTableAlias()) {
                $errorCollectionTransfer->addError(
                    (new ErrorTransfer())
                        ->setMessage(sprintf(static::ERROR_MESSAGE_TABLE_ALIAS, $existingDynamicEntityConfiguration->getTableName()))
                        ->setParameters([static::TYPE => static::TABLE_ALIAS])
                        ->setEntityIdentifier($existingDynamicEntityConfiguration->getTableName()),
                );
            }
        }

        return $errorCollectionTransfer;
    }
}
