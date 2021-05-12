<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderDataExport\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CommentTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToUtilEncodingServiceInterface;

class MerchantSalesOrderCommentMapper
{
    /**
     * @var \Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\MerchantSalesOrderDataExport\Dependency\Service\MerchantSalesOrderDataExportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(MerchantSalesOrderDataExportToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @phpstan-param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Sales\Persistence\SpySalesOrderComment> $salesOrderCommentEntities
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $salesOrderCommentEntities
     * @param \Generated\Shared\Transfer\CommentTransfer[] $commentTransfers
     *
     * @return \Generated\Shared\Transfer\CommentTransfer[]
     */
    public function mapMerchantSalesOrderCommentEntitiesToCommentTransfersByIdSalesOrder(
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
     * @param \Generated\Shared\Transfer\CommentTransfer[] $merchantSalesOrderCommentTransfers
     *
     * @return string|null
     */
    public function mapMerchantSalesOrderCommentTransfersToJson(array $merchantSalesOrderCommentTransfers): ?string
    {
        $merchantOrderComments = [];
        foreach ($merchantSalesOrderCommentTransfers as $merchantSalesOrderCommentTransfer) {
            $merchantOrderComments[] = [
                'username' => $merchantSalesOrderCommentTransfer->getUsername(),
                'message' => $merchantSalesOrderCommentTransfer->getMessage(),
                'created_at' => $merchantSalesOrderCommentTransfer->getCreatedAt(),
                'updated_at' => $merchantSalesOrderCommentTransfer->getUpdatedAt(),
            ];
        }

        return $this->utilEncodingService->encodeJson($merchantOrderComments);
    }
}
