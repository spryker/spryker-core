<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Updater;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer;
use Spryker\Zed\DynamicEntity\Business\Configuration\DynamicEntityConfigurationResponseInterface;
use Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface;
use Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface;
use Spryker\Zed\DynamicEntity\Business\Transaction\Propel\TransactionProcessorInterface;
use Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;

class DynamicEntityUpdater implements DynamicEntityUpdaterInterface
{
    use InstancePoolingTrait;

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
     * @var \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface
     */
    protected DynamicEntityMapperInterface $dynamicEntityMapper;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Transaction\Propel\TransactionProcessorInterface
     */
    protected TransactionProcessorInterface $transactionProcessor;

    /**
     * @var array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostUpdatePluginInterface>
     */
    protected array $dynamicEntityPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface $dynamicEntityReader
     * @param \Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface $dynamicEntityWriter
     * @param \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface $dynamicEntityMapper
     * @param \Spryker\Zed\DynamicEntity\Business\Transaction\Propel\TransactionProcessorInterface $transactionProcessor
     * @param array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostUpdatePluginInterface> $dynamicEntityPostUpdatePlugins
     */
    public function __construct(
        DynamicEntityReaderInterface $dynamicEntityReader,
        DynamicEntityWriterInterface $dynamicEntityWriter,
        DynamicEntityMapperInterface $dynamicEntityMapper,
        TransactionProcessorInterface $transactionProcessor,
        array $dynamicEntityPostUpdatePlugins
    ) {
        $this->dynamicEntityReader = $dynamicEntityReader;
        $this->dynamicEntityWriter = $dynamicEntityWriter;
        $this->dynamicEntityMapper = $dynamicEntityMapper;
        $this->transactionProcessor = $transactionProcessor;
        $this->dynamicEntityPostUpdatePlugins = $dynamicEntityPostUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function update(DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer): DynamicEntityCollectionResponseTransfer
    {
        $isInstancePoolingEnabled = $this->isInstancePoolingEnabled();
        if ($isInstancePoolingEnabled === true) {
            $this->disableInstancePooling();
        }
        $dynamicEntityConfigurationResponse = $this->dynamicEntityReader->getDynamicEntityConfigurationTransferTree(
            $dynamicEntityCollectionRequestTransfer,
        );

        $dynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();

        if ($dynamicEntityConfigurationResponse->getErrorTransfers() !== []) {
            return $this->mergeErrors($dynamicEntityCollectionResponseTransfer, $dynamicEntityConfigurationResponse);
        }

        $this->transactionProcessor->startAtomicTransaction($dynamicEntityCollectionRequestTransfer);

        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $currentDynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();

            $this->transactionProcessor->startPerItemTransaction($dynamicEntityCollectionRequestTransfer);

            $currentDynamicEntityCollectionResponseTransfer = $this->dynamicEntityWriter->updateDynamicEntity(
                $dynamicEntityTransfer,
                $dynamicEntityConfigurationResponse->getDynamicEntityConfigurationTransfer(),
                $dynamicEntityCollectionRequestTransfer,
                $currentDynamicEntityCollectionResponseTransfer,
            );

            $this->transactionProcessor->endPerItemTransaction($dynamicEntityCollectionRequestTransfer, $currentDynamicEntityCollectionResponseTransfer);

            $dynamicEntityCollectionResponseTransfer = $this->mergeResponses($dynamicEntityCollectionResponseTransfer, $currentDynamicEntityCollectionResponseTransfer);
        }

        $dynamicEntityCollectionResponseTransfer = $this->executeDynamicEntityPostUpdatePlugins(
            $dynamicEntityConfigurationResponse->getDynamicEntityConfigurationTransfer(),
            $dynamicEntityCollectionResponseTransfer,
        );

        $this->transactionProcessor->endAtomicTransaction($dynamicEntityCollectionRequestTransfer, $dynamicEntityCollectionResponseTransfer);

        if ($isInstancePoolingEnabled === true) {
            $this->enableInstancePooling();
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function executeDynamicEntityPostUpdatePlugins(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityPostEditRequestTransfers = $this->dynamicEntityMapper
            ->mapDynamicEntityCollectionResponseTransferToPostEditRequestTransfersArray(
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityCollectionResponseTransfer,
            );

        foreach ($dynamicEntityPostEditRequestTransfers as $dynamicEntityPostEditRequestTransfer) {
            $dynamicEntityPostEditResponseTransfer = $this->executeDynamicEntityPostUpdatePluginsForPostEditRequestTransfer($dynamicEntityPostEditRequestTransfer);

            foreach ($dynamicEntityPostEditResponseTransfer->getErrors() as $error) {
                $dynamicEntityCollectionResponseTransfer->addError($error);
            }
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityPostEditResponseTransfer
     */
    protected function executeDynamicEntityPostUpdatePluginsForPostEditRequestTransfer(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        $dynamicEntityPostEditResponseTransfer = null;
        foreach ($this->dynamicEntityPostUpdatePlugins as $dynamicEntityPostUpdatePlugin) {
            $dynamicEntityPostEditResponseTransfer = $dynamicEntityPostUpdatePlugin->postUpdate(
                $dynamicEntityPostEditRequestTransfer,
            );

            if (count($dynamicEntityPostEditResponseTransfer->getErrors()) > 0) {
                return $dynamicEntityPostEditResponseTransfer;
            }
        }

        return $dynamicEntityPostEditResponseTransfer ?: new DynamicEntityPostEditResponseTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Spryker\Zed\DynamicEntity\Business\Configuration\DynamicEntityConfigurationResponseInterface $dynamicEntityConfiguration
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function mergeErrors(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityConfigurationResponseInterface $dynamicEntityConfiguration
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityConfiguration->getErrorTransfers() as $errorTransfer) {
            $dynamicEntityCollectionResponseTransfer->addError($errorTransfer);
        }

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $mainDynamicEntityCollectionResponseTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $currentDynamicEntityCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function mergeResponses(
        DynamicEntityCollectionResponseTransfer $mainDynamicEntityCollectionResponseTransfer,
        DynamicEntityCollectionResponseTransfer $currentDynamicEntityCollectionResponseTransfer
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($currentDynamicEntityCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $mainDynamicEntityCollectionResponseTransfer->addError($errorTransfer);
        }

        foreach ($currentDynamicEntityCollectionResponseTransfer->getDynamicEntities() as $dynamicEntityTransfer) {
            $mainDynamicEntityCollectionResponseTransfer->addDynamicEntity($dynamicEntityTransfer);
        }

        return $mainDynamicEntityCollectionResponseTransfer;
    }
}
