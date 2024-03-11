<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Writer;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface;
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
     * @var \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface
     */
    protected DynamicEntityMapperInterface $dynamicEntityMapper;

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
     * @param \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface $dynamicEntityMapper
     * @param array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostCreatePluginInterface> $dynamicEntityPostCreatePlugins
     * @param array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostUpdatePluginInterface> $dynamicEntityPostUpdatePlugins
     */
    public function __construct(
        DynamicEntityEntityManagerInterface $entityManager,
        DynamicEntityToConnectionInterface $propelConnection,
        DynamicEntityMapperInterface $dynamicEntityMapper,
        array $dynamicEntityPostCreatePlugins,
        array $dynamicEntityPostUpdatePlugins = []
    ) {
        $this->entityManager = $entityManager;
        $this->propelConnection = $propelConnection;
        $this->dynamicEntityMapper = $dynamicEntityMapper;
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
        $dynamicEntityPostEditRequestTransfers = $this->dynamicEntityMapper
            ->mapDynamicEntityCollectionResponseTransferToPostEditRequestTransfersArray(
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityCollectionResponseTransfer,
            );

        foreach ($dynamicEntityPostEditRequestTransfers as $dynamicEntityPostEditRequestTransfer) {
            $dynamicEntityPostEditResponseTransfer = $this->executeDynamicEntityPostCreatePluginsForPostEditRequestTransfer($dynamicEntityPostEditRequestTransfer);

            if (count($dynamicEntityPostEditResponseTransfer->getErrors()) > 0) {
                return $dynamicEntityPostEditResponseTransfer;
            }
        }

        return $dynamicEntityPostEditResponseTransfer ?? new DynamicEntityPostEditResponseTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    protected function executeDynamicEntityPostCreatePluginsForPostEditRequestTransfer(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        foreach ($this->dynamicEntityPostCreatePlugins as $dynamicEntityPostCreatePlugin) {
            $dynamicEntityPostEditResponseTransfer = $dynamicEntityPostCreatePlugin->postCreate(
                $dynamicEntityPostEditRequestTransfer,
            );

            if (count($dynamicEntityPostEditResponseTransfer->getErrors()) > 0) {
                return $dynamicEntityPostEditResponseTransfer;
            }
        }

        return $dynamicEntityPostEditResponseTransfer ?? new DynamicEntityPostEditResponseTransfer();
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
        $dynamicEntityPostEditRequestTransfers = $this->dynamicEntityMapper
            ->mapDynamicEntityCollectionResponseTransferToPostEditRequestTransfersArray(
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityCollectionResponseTransfer,
            );

        foreach ($dynamicEntityPostEditRequestTransfers as $dynamicEntityPostEditRequestTransfer) {
            $dynamicEntityPostEditResponseTransfer = $this->executeDynamicEntityPostUpdatePluginsForPostEditRequestTransfer($dynamicEntityPostEditRequestTransfer);

            if (count($dynamicEntityPostEditResponseTransfer->getErrors()) > 0) {
                return $dynamicEntityPostEditResponseTransfer;
            }
        }

        return $dynamicEntityPostEditResponseTransfer ?? new DynamicEntityPostEditResponseTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    protected function executeDynamicEntityPostUpdatePluginsForPostEditRequestTransfer(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        foreach ($this->dynamicEntityPostUpdatePlugins as $dynamicEntityPostUpdatePlugin) {
            $dynamicEntityPostEditResponseTransfer = $dynamicEntityPostUpdatePlugin->postUpdate(
                $dynamicEntityPostEditRequestTransfer,
            );

            if (count($dynamicEntityPostEditResponseTransfer->getErrors()) > 0) {
                return $dynamicEntityPostEditResponseTransfer;
            }
        }

        return $dynamicEntityPostEditResponseTransfer ?? new DynamicEntityPostEditResponseTransfer();
    }
}
