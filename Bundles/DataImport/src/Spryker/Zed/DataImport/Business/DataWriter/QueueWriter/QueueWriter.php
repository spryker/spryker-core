<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DataWriter\QueueWriter;

use Generated\Shared\Transfer\DataSetItemTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;

class QueueWriter implements QueueWriterInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface
     */
    protected $queueClient;

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Client\DataImportToQueueClientInterface $queueClient
     * @param \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        DataImportToQueueClientInterface $queueClient,
        DataImportToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->queueClient = $queueClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $queueName
     * @param \Generated\Shared\Transfer\DataSetItemTransfer[] $dataSetItemTransfers
     *
     * @return void
     */
    public function write(string $queueName, array $dataSetItemTransfers): void
    {
        $queueSendMessageTransfers = $this->getQueueSendMessageTransfersFromDataSetItemTransfers($dataSetItemTransfers);

        if (!$queueSendMessageTransfers) {
            return;
        }

        $this->queueClient->sendMessages($queueName, $queueSendMessageTransfers);
    }

    /**
     * @param array $dataSetItemTransfers
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer[]
     */
    protected function getQueueSendMessageTransfersFromDataSetItemTransfers(array $dataSetItemTransfers): array
    {
        $queueSendMessageTransfers = [];

        foreach ($dataSetItemTransfers as $dataSetItemTransfer) {
            $queueSendMessageTransfer = $this->mapDataSetItemTransferToQueueSendMessageTransfer($dataSetItemTransfer);

            if (!$queueSendMessageTransfer) {
                continue;
            }

            $queueSendMessageTransfers[] = $queueSendMessageTransfer;
        }

        return $queueSendMessageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DataSetItemTransfer $dataSetItemTransfer
     *
     * @return \Generated\Shared\Transfer\QueueSendMessageTransfer|null
     */
    protected function mapDataSetItemTransferToQueueSendMessageTransfer(DataSetItemTransfer $dataSetItemTransfer): ?QueueSendMessageTransfer
    {
        if (!$dataSetItemTransfer->getPayload()) {
            return null;
        }

        $encodedData = $this->utilEncodingService->encodeJson($dataSetItemTransfer->getPayload());

        return (new QueueSendMessageTransfer())->setBody($encodedData);
    }
}
