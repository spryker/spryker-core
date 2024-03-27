<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationRequestConnector\Business\Expander;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Spryker\Zed\CommentMerchantRelationRequestConnector\Business\Reader\CommentReaderInterface;
use Spryker\Zed\CommentMerchantRelationRequestConnector\CommentMerchantRelationRequestConnectorConfig;

class CommentThreadExpander implements CommentThreadExpanderInterface
{
    /**
     * @var \Spryker\Zed\CommentMerchantRelationRequestConnector\Business\Reader\CommentReaderInterface
     */
    protected CommentReaderInterface $commentReader;

    /**
     * @param \Spryker\Zed\CommentMerchantRelationRequestConnector\Business\Reader\CommentReaderInterface $commentReader
     */
    public function __construct(CommentReaderInterface $commentReader)
    {
        $this->commentReader = $commentReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function expandMerchantRelationRequestCollection(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer {
        $indexedCommentThreadTransfers = $this->commentReader->getCommentThreadsIndexedByOwnerId(
            CommentMerchantRelationRequestConnectorConfig::COMMENT_THREAD_MERCHANT_RELATION_REQUEST_OWNER_TYPE,
            $this->extractMerchantRelationRequestIds($merchantRelationRequestCollectionTransfer),
        );

        foreach ($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests() as $merchantRelationRequestTransfer) {
            $idMerchantRelationRequest = $merchantRelationRequestTransfer->getIdMerchantRelationRequestOrFail();
            $commentThreadTransfer = $indexedCommentThreadTransfers[$idMerchantRelationRequest] ?? null;

            if ($commentThreadTransfer) {
                $merchantRelationRequestTransfer->setCommentThread($commentThreadTransfer);
            }
        }

        return $merchantRelationRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return list<int>
     */
    protected function extractMerchantRelationRequestIds(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): array {
        $merchantRelationRequestIds = [];
        foreach ($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests() as $merchantRelationRequestTransfer) {
            $merchantRelationRequestIds[] = $merchantRelationRequestTransfer->getIdMerchantRelationRequestOrFail();
        }

        return array_unique($merchantRelationRequestIds);
    }
}
