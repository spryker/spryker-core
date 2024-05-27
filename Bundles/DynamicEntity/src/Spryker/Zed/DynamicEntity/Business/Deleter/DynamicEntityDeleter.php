<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Deleter;

use Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface;
use Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface;

class DynamicEntityDeleter implements DynamicEntityDeleterInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_ENTITY_NOT_EXIST = 'dynamic_entity.validation.entity_does_not_exist';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_METHOD_NOT_ALLOWED = 'dynamic_entity.validation.method_not_allowed';

    /**
     * @var string
     */
    protected const PLACEHOLDER_ALIAS_NAME = '%aliasName%';

    /**
     * @var string
     */
    protected const ERROR_PATH_PLACEHOLDER = '%s[%d]';

    /**
     * @var int
     */
    protected const ERROR_PATH_INDEX = 0;

    /**
     * @var \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface
     */
    protected DynamicEntityEntityManagerInterface $entityManager;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface
     */
    protected DynamicEntityMapperInterface $dynamicEntityMapper;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface
     */
    protected DynamicEntityReaderInterface $dynamicEntityReader;

    /**
     * @param \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface $entityManager
     * @param \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface $dynamicEntityMapper
     * @param \Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface $dynamicEntityReader
     */
    public function __construct(
        DynamicEntityEntityManagerInterface $entityManager,
        DynamicEntityMapperInterface $dynamicEntityMapper,
        DynamicEntityReaderInterface $dynamicEntityReader
    ) {
        $this->entityManager = $entityManager;
        $this->dynamicEntityMapper = $dynamicEntityMapper;
        $this->dynamicEntityReader = $dynamicEntityReader;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function deleteEntityCollection(
        DynamicEntityCollectionDeleteCriteriaTransfer $dynamicEntityCollectionDeleteCriteriaTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityCriteriaTransfer = $this->dynamicEntityMapper->mapDynamicEntityCollectionDeleteCriteriaTransferToDynamicEntityCriteriaTransfer(
            $dynamicEntityCollectionDeleteCriteriaTransfer,
            new DynamicEntityCriteriaTransfer(),
        );

        $dynamicEntityConfigurationTransfer = $this->getDynamicEntityConfigurationTransfer($dynamicEntityCriteriaTransfer);

        if ($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail()->getIsDeletable() !== true) {
            return $this->createErrorResponse($dynamicEntityCriteriaTransfer, static::ERROR_MESSAGE_METHOD_NOT_ALLOWED);
        }

        $dynamicEntityCollectionTransfer = $this->dynamicEntityReader->getEntityCollection($dynamicEntityCriteriaTransfer);

        if ($dynamicEntityCollectionTransfer->getDynamicEntities()->count() === 0) {
            return $this->createErrorResponse($dynamicEntityCriteriaTransfer, static::ERROR_MESSAGE_ENTITY_NOT_EXIST);
        }

        if ($dynamicEntityCollectionTransfer->getErrors()->count() !== 0) {
            return $this->createDynamicEntityCollectionResponseTransfer($dynamicEntityCollectionTransfer);
        }

        return $this->entityManager->deleteDynamicEntity(
            $dynamicEntityCollectionTransfer,
            $dynamicEntityConfigurationTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function createErrorResponse(
        DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer,
        string $errorMessage
    ): DynamicEntityCollectionResponseTransfer {
        $errorTransfer = (new ErrorTransfer())
            ->setMessage($errorMessage)
            ->setParameters([
                static::PLACEHOLDER_ALIAS_NAME => $dynamicEntityCriteriaTransfer->getDynamicEntityConditionsOrFail()->getTableAliasOrFail(),
            ]);

        return (new DynamicEntityCollectionResponseTransfer())
            ->addError($errorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function createDynamicEntityCollectionResponseTransfer(
        DynamicEntityCollectionTransfer $dynamicEntityCollectionTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();
        foreach ($dynamicEntityCollectionTransfer->getErrors() as $errorTransfer) {
            $dynamicEntityCollectionResponseTransfer->addError($errorTransfer);
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer
     */
    protected function getDynamicEntityConfigurationTransfer(DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer): DynamicEntityConfigurationTransfer
    {
        $tableAlias = $dynamicEntityCriteriaTransfer->getDynamicEntityConditionsOrFail()->getTableAliasOrFail();
        $dynamicEntityConfigurationResponse = $this->dynamicEntityReader->getDynamicEntityConfigurationTransferTree((new DynamicEntityCollectionRequestTransfer())->setTableAlias($tableAlias));

        return $dynamicEntityConfigurationResponse->getDynamicEntityConfigurationTransfer();
    }

    /**
     * @param string $tableAlias
     *
     * @return string
     */
    protected function buildErrorPath(string $tableAlias): string
    {
        return sprintf(static::ERROR_PATH_PLACEHOLDER, $tableAlias, static::ERROR_PATH_INDEX);
    }
}
