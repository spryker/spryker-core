<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CommentTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToUtilEncodingServiceInterface;

class SalesOrderCommentMapper
{
    /**
     * @var \Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(SalesDataExportToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\Base\SpySalesOrderComment[] $salesOrderCommentEntities
     * @param \Generated\Shared\Transfer\CommentTransfer[] $commentTransfers
     *
     * @return \Generated\Shared\Transfer\CommentTransfer[]
     */
    public function mapSalesOrderCommentEntitiesToCommentTransfersByIdSalesOrder(
        ObjectCollection $salesOrderCommentEntities,
        array $commentTransfers
    ): array {
        foreach ($salesOrderCommentEntities as $salesOrderCommentEntity) {
            $commentTransfers[$salesOrderCommentEntity->getFkSalesOrder()][] = (new CommentTransfer())->fromArray(
                $salesOrderCommentEntity->toArray(),
                true
            );
        }

        return $commentTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer[] $salesOrderCommentTransfers
     *
     * @return string
     */
    public function mapSalesOrderCommentTransfersToJson(array $salesOrderCommentTransfers): string
    {
        $orderComments = [];
        foreach ($salesOrderCommentTransfers as $salesOrderCommentTransfer) {
            $orderComments[] = [
                'username' => $salesOrderCommentTransfer->getUsername(),
                'message' => $salesOrderCommentTransfer->getMessage(),
                'created_at' => $salesOrderCommentTransfer->getCreatedAt(),
                'updated_at' => $salesOrderCommentTransfer->getUpdatedAt(),
            ];
        }

        return $this->utilEncodingService->encodeJson($orderComments);
    }
}
