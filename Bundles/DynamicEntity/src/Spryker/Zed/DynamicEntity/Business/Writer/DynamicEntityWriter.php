<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Writer;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityDefinitionTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityTransfer;
use Generated\Shared\Transfer\RawDynamicEntityTransfer;
use Spryker\Zed\DynamicEntity\Dependency\External\DynamicEntityToConnectionInterface;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface;

class DynamicEntityWriter implements DynamicEntityWriterInterface
{
    /**
     * @var \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface
     */
    protected DynamicEntityEntityManagerInterface $entityManager;

    /**
     * @var \Spryker\Zed\DynamicEntity\Dependency\External\DynamicEntityToConnectionInterface
     */
    protected DynamicEntityToConnectionInterface $propelConnection;

    /**
     * @var array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostCreatePluginInterface>
     */
    protected array $dynamicEntityPostCreatePlugins;

    /**
     * @var array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostUpdatePluginInterface>
     */
    protected array $dynamicEntityPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface $entityManager
     * @param \Spryker\Zed\DynamicEntity\Dependency\External\DynamicEntityToConnectionInterface $propelConnection
     * @param array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostCreatePluginInterface> $dynamicEntityPostCreatePlugins
     * @param array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostUpdatePluginInterface> $dynamicEntityPostUpdatePlugins
     */
    public function __construct(
        DynamicEntityEntityManagerInterface $entityManager,
        DynamicEntityToConnectionInterface $propelConnection,
        array $dynamicEntityPostCreatePlugins,
        array $dynamicEntityPostUpdatePlugins = []
    ) {
        $this->entityManager = $entityManager;
        $this->propelConnection = $propelConnection;
        $this->dynamicEntityPostCreatePlugins = $dynamicEntityPostCreatePlugins;
        $this->dynamicEntityPostUpdatePlugins = $dynamicEntityPostUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function executeCreateTransaction(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $this->startTransaction();

        $dynamicEntityCollectionResponseTransfer = $this->entityManager->createDynamicEntityCollection($dynamicEntityCollectionRequestTransfer, $dynamicEntityConfigurationTransfer);

        if ($dynamicEntityCollectionResponseTransfer->getErrors()->count() === 0) {
            $dynamicEntityPostEditResponseTransfer = $this->executeDynamicEntityPostCreatePlugins($dynamicEntityConfigurationTransfer, $dynamicEntityCollectionResponseTransfer);
            foreach ($dynamicEntityPostEditResponseTransfer->getErrors() as $error) {
                $dynamicEntityCollectionResponseTransfer->addError($error);
            }
        }

        $this->endTransaction($dynamicEntityCollectionResponseTransfer);

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function executeUpdateTransaction(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $this->startTransaction();

        $dynamicEntityCollectionResponseTransfer = $this->executeUpdateWithoutTransaction($dynamicEntityCollectionRequestTransfer, $dynamicEntityConfigurationTransfer);

        $this->endTransaction($dynamicEntityCollectionResponseTransfer);

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function executeUpdateWithoutTransaction(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityCollectionResponseTransfer = $this->entityManager->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer, $dynamicEntityConfigurationTransfer);

        if ($dynamicEntityCollectionResponseTransfer->getErrors()->count() === 0) {
            $dynamicEntityPostEditResponseTransfer = $this->executeDynamicEntityPostUpdatePlugins($dynamicEntityConfigurationTransfer, $dynamicEntityCollectionResponseTransfer);
            foreach ($dynamicEntityPostEditResponseTransfer->getErrors() as $error) {
                $dynamicEntityCollectionResponseTransfer->addError($error);
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @return bool
     */
    public function startTransaction(): bool
    {
        return $this->propelConnection->beginTransaction();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return bool
     */
    public function endTransaction(DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer): bool
    {
        if (count($dynamicEntityCollectionResponseTransfer->getErrors()) > 0) {
            return $this->propelConnection->rollBack();
        }

        return $this->propelConnection->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    protected function executeDynamicEntityPostCreatePlugins(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): DynamicEntityPostEditResponseTransfer {
        $dynamicEntityPostEditResponseTransfer = new DynamicEntityPostEditResponseTransfer();
        foreach ($this->dynamicEntityPostCreatePlugins as $dynamicEntityPostCreatePlugin) {
            $dynamicEntityPostCreatePlugin->postCreate(
                $this->createDynamicEntityPostEditRequestTransfer(
                    $dynamicEntityConfigurationTransfer,
                    $dynamicEntityCollectionResponseTransfer,
                ),
            );
            if (count($dynamicEntityPostEditResponseTransfer->getErrors()) > 0) {
                return $dynamicEntityPostEditResponseTransfer;
            }
        }

        return $dynamicEntityPostEditResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    protected function executeDynamicEntityPostUpdatePlugins(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): DynamicEntityPostEditResponseTransfer {
        $dynamicEntityPostEditResponseTransfer = new DynamicEntityPostEditResponseTransfer();
        foreach ($this->dynamicEntityPostUpdatePlugins as $dynamicEntityPostUpdatePlugin) {
            $dynamicEntityPostEditResponseTransfer = $dynamicEntityPostUpdatePlugin->postUpdate(
                $this->createDynamicEntityPostEditRequestTransfer(
                    $dynamicEntityConfigurationTransfer,
                    $dynamicEntityCollectionResponseTransfer,
                ),
            );

            if (count($dynamicEntityPostEditResponseTransfer->getErrors()) > 0) {
                return $dynamicEntityPostEditResponseTransfer;
            }
        }

        return $dynamicEntityPostEditResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer
     */
    protected function createDynamicEntityPostEditRequestTransfer(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): DynamicEntityPostEditRequestTransfer {
        $dynamicEntityPostEditRequestTransfer = new DynamicEntityPostEditRequestTransfer();
        $indexedFieldDefinitions = $this->getFieldNamesIndexedByFieldVisibleName($dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail());
        foreach ($dynamicEntityCollectionResponseTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $dynamicEntityFields = $this->getFieldValuesIndexedByFieldName($dynamicEntityTransfer, $indexedFieldDefinitions);
            $dynamicEntityPostEditRequestTransfer->addRawDynamicEntity(
                (new RawDynamicEntityTransfer())->setFields($dynamicEntityFields),
            );
        }

        return $dynamicEntityPostEditRequestTransfer
            ->setTableName($dynamicEntityConfigurationTransfer->getTableNameOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityTransfer $dynamicEntityTransfer
     * @param array<string, string> $indexedFieldDefinitions
     *
     * @return array<string, string>
     */
    protected function getFieldValuesIndexedByFieldName(
        DynamicEntityTransfer $dynamicEntityTransfer,
        array $indexedFieldDefinitions
    ): array {
        $dynamicEntityFields = [];

        foreach ($dynamicEntityTransfer->getFields() as $fieldName => $fieldValue) {
            $dynamicEntityFields[$indexedFieldDefinitions[$fieldName]] = $fieldValue;
        }

        return $dynamicEntityFields;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer
     *
     * @return array<string, string>
     */
    protected function getFieldNamesIndexedByFieldVisibleName(DynamicEntityDefinitionTransfer $dynamicEntityDefinitionTransfer): array
    {
        $result = [];

        foreach ($dynamicEntityDefinitionTransfer->getFieldDefinitions() as $fieldDefinition) {
            $result[$fieldDefinition->getFieldVisibleNameOrFail()] = $fieldDefinition->getFieldNameOrFail();
        }

        return $result;
    }
}
