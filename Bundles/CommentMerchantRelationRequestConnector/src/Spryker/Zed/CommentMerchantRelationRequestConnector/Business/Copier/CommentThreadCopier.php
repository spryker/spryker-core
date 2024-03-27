<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationRequestConnector\Business\Copier;

use Generated\Shared\Transfer\CommentFilterTransfer;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\CommentMerchantRelationRequestConnector\Business\Reader\MerchantRelationRequestReaderInterface;
use Spryker\Zed\CommentMerchantRelationRequestConnector\Dependency\Facade\CommentMerchantRelationRequestConnectorToCommentFacadeInterface;

class CommentThreadCopier implements CommentThreadCopierInterface
{
    /**
     * @uses \Spryker\Zed\CommentMerchantRelationshipConnector\CommentMerchantRelationshipConnectorConfig::COMMENT_THREAD_MERCHANT_RELATIONSHIP_OWNER_TYPE
     *
     * @var string
     */
    protected const COMMENT_THREAD_MERCHANT_RELATIONSHIP_OWNER_TYPE = 'merchant_relationship';

    /**
     * @var \Spryker\Zed\CommentMerchantRelationRequestConnector\Dependency\Facade\CommentMerchantRelationRequestConnectorToCommentFacadeInterface
     */
    protected CommentMerchantRelationRequestConnectorToCommentFacadeInterface $commentFacade;

    /**
     * @var \Spryker\Zed\CommentMerchantRelationRequestConnector\Business\Reader\MerchantRelationRequestReaderInterface
     */
    protected MerchantRelationRequestReaderInterface $merchantRelationRequestReader;

    /**
     * @param \Spryker\Zed\CommentMerchantRelationRequestConnector\Dependency\Facade\CommentMerchantRelationRequestConnectorToCommentFacadeInterface $commentFacade
     * @param \Spryker\Zed\CommentMerchantRelationRequestConnector\Business\Reader\MerchantRelationRequestReaderInterface $merchantRelationRequestReader
     */
    public function __construct(
        CommentMerchantRelationRequestConnectorToCommentFacadeInterface $commentFacade,
        MerchantRelationRequestReaderInterface $merchantRelationRequestReader
    ) {
        $this->commentFacade = $commentFacade;
        $this->merchantRelationRequestReader = $merchantRelationRequestReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function copyCommentThreadsFromMerchantRelationRequests(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): MerchantRelationshipCollectionTransfer {
        $indexedMerchantRelationRequestTransfers = $this->merchantRelationRequestReader
            ->getMerchantRelationRequestTransfersIndexedByUuid(
                $this->extractMerchantRelationRequestUuids($merchantRelationshipCollectionTransfer),
            );

        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $merchantRelationRequestUuid = $merchantRelationshipTransfer->getMerchantRelationRequestUuid();
            if (!$merchantRelationRequestUuid) {
                continue;
            }

            $merchantRelationRequestTransfer = $indexedMerchantRelationRequestTransfers[$merchantRelationRequestUuid] ?? null;
            if (!$merchantRelationRequestTransfer || !$merchantRelationRequestTransfer->getCommentThread()) {
                continue;
            }

            $merchantRelationshipTransfer->setCommentThread(
                $this->copyCommentThread($merchantRelationshipTransfer, $merchantRelationRequestTransfer),
            );
        }

        return $merchantRelationshipCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    protected function copyCommentThread(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): ?CommentThreadTransfer {
        $commentThreadTransfer = $merchantRelationRequestTransfer->getCommentThreadOrFail();
        $commentFilterTransfer = (new CommentFilterTransfer())
            ->setOwnerId($commentThreadTransfer->getOwnerIdOrFail())
            ->setOwnerType($commentThreadTransfer->getOwnerTypeOrFail());

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($merchantRelationshipTransfer->getIdMerchantRelationshipOrFail())
            ->setOwnerType(static::COMMENT_THREAD_MERCHANT_RELATIONSHIP_OWNER_TYPE);

        return $this->commentFacade
            ->duplicateCommentThread($commentFilterTransfer, $commentRequestTransfer)
            ->getCommentThread();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractMerchantRelationRequestUuids(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): array {
        $merchantRelationRequestUuids = [];
        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationRequestTransfer) {
            if ($merchantRelationRequestTransfer->getMerchantRelationRequestUuid()) {
                $merchantRelationRequestUuids[] = $merchantRelationRequestTransfer->getMerchantRelationRequestUuidOrFail();
            }
        }

        return array_unique($merchantRelationRequestUuids);
    }
}
