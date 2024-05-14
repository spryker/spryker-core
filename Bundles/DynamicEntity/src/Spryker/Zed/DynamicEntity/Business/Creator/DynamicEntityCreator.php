<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Creator;

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

class DynamicEntityCreator implements DynamicEntityCreatorInterface
{
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
     * @var array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostCreatePluginInterface>
     */
    protected array $dynamicEntityPostCreatePlugins;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Reader\DynamicEntityReaderInterface $dynamicEntityReader
     * @param \Spryker\Zed\DynamicEntity\Business\Writer\DynamicEntityWriterInterface $dynamicEntityWriter
     * @param \Spryker\Zed\DynamicEntity\Business\Mapper\DynamicEntityMapperInterface $dynamicEntityMapper
     * @param \Spryker\Zed\DynamicEntity\Business\Transaction\Propel\TransactionProcessorInterface $transactionProcessor
     * @param array<\Spryker\Zed\DynamicEntityExtension\Dependency\Plugin\DynamicEntityPostCreatePluginInterface> $dynamicEntityPostCreatePlugins
     */
    public function __construct(
        DynamicEntityReaderInterface $dynamicEntityReader,
        DynamicEntityWriterInterface $dynamicEntityWriter,
        DynamicEntityMapperInterface $dynamicEntityMapper,
        TransactionProcessorInterface $transactionProcessor,
        array $dynamicEntityPostCreatePlugins
    ) {
        $this->dynamicEntityReader = $dynamicEntityReader;
        $this->dynamicEntityWriter = $dynamicEntityWriter;
        $this->dynamicEntityMapper = $dynamicEntityMapper;
        $this->transactionProcessor = $transactionProcessor;
        $this->dynamicEntityPostCreatePlugins = $dynamicEntityPostCreatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function create(DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer): DynamicEntityCollectionResponseTransfer
    {
        $dynamicEntityConfigurationResponse = $this->dynamicEntityReader->getDynamicEntityConfigurationTransferTree(
            $dynamicEntityCollectionRequestTransfer,
        );

        $dynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();

        if ($dynamicEntityConfigurationResponse->getErrorTransfers() !== []) {
            return $this->mergeErrors($dynamicEntityCollectionResponseTransfer, $dynamicEntityConfigurationResponse);
        }

        $this->transactionProcessor->startAtomicTransaction($dynamicEntityCollectionRequestTransfer);

        foreach ($dynamicEntityCollectionRequestTransfer->getDynamicEntities() as $index => $dynamicEntityTransfer) {
            $currentDynamicEntityCollectionResponseTransfer = new DynamicEntityCollectionResponseTransfer();

            $this->transactionProcessor->startPerItemTransaction($dynamicEntityCollectionRequestTransfer);

            $currentDynamicEntityCollectionResponseTransfer = $this->dynamicEntityWriter->createDynamicEntity(
                $dynamicEntityTransfer,
                $dynamicEntityConfigurationResponse->getDynamicEntityConfigurationTransfer(),
                $dynamicEntityCollectionRequestTransfer,
                $currentDynamicEntityCollectionResponseTransfer,
            );

            $this->transactionProcessor->endPerItemTransaction($dynamicEntityCollectionRequestTransfer, $currentDynamicEntityCollectionResponseTransfer);

            $dynamicEntityCollectionResponseTransfer = $this->mergeResponses($dynamicEntityCollectionResponseTransfer, $currentDynamicEntityCollectionResponseTransfer);
        }

        $dynamicEntityCollectionResponseTransfer = $this->executeDynamicEntityPostCreatePlugins(
            $dynamicEntityConfigurationResponse->getDynamicEntityConfigurationTransfer(),
            $dynamicEntityCollectionResponseTransfer,
        );

        $this->transactionProcessor->endAtomicTransaction($dynamicEntityCollectionRequestTransfer, $dynamicEntityCollectionResponseTransfer);

        return $dynamicEntityCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function executeDynamicEntityPostCreatePlugins(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): DynamicEntityCollectionResponseTransfer {
        $dynamicEntityPostEditResponseTransfer = new DynamicEntityPostEditResponseTransfer();
        $dynamicEntityPostEditRequestTransfers = $this->dynamicEntityMapper
            ->mapDynamicEntityCollectionResponseTransferToPostEditRequestTransfersArray(
                $dynamicEntityConfigurationTransfer,
                $dynamicEntityCollectionResponseTransfer,
            );

        foreach ($dynamicEntityPostEditRequestTransfers as $dynamicEntityPostEditRequestTransfer) {
            $dynamicEntityPostEditResponseTransfer = $this->executeDynamicEntityPostCreatePluginsForPostEditRequestTransfer($dynamicEntityPostEditRequestTransfer);

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
    protected function executeDynamicEntityPostCreatePluginsForPostEditRequestTransfer(
        DynamicEntityPostEditRequestTransfer $dynamicEntityPostEditRequestTransfer
    ): DynamicEntityPostEditResponseTransfer {
        $dynamicEntityPostEditResponseTransfer = null;
        foreach ($this->dynamicEntityPostCreatePlugins as $dynamicEntityPostCreatePlugin) {
            $dynamicEntityPostEditResponseTransfer = $dynamicEntityPostCreatePlugin->postCreate(
                $dynamicEntityPostEditRequestTransfer,
            );

            if ($dynamicEntityPostEditResponseTransfer->getErrors()->count() > 0) {
                return $dynamicEntityPostEditResponseTransfer;
            }
        }

        return $dynamicEntityPostEditResponseTransfer ?: new DynamicEntityPostEditResponseTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     * @param \Spryker\Zed\DynamicEntity\Business\Configuration\DynamicEntityConfigurationResponseInterface $dynamicEntityConfigurationResponse
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    protected function mergeErrors(
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer,
        DynamicEntityConfigurationResponseInterface $dynamicEntityConfigurationResponse
    ): DynamicEntityCollectionResponseTransfer {
        foreach ($dynamicEntityConfigurationResponse->getErrorTransfers() as $errorTransfer) {
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
