<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationshipConnector\Business\Expander;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Spryker\Zed\CommentMerchantRelationshipConnector\Business\Reader\CommentReaderInterface;
use Spryker\Zed\CommentMerchantRelationshipConnector\CommentMerchantRelationshipConnectorConfig;

class CommentThreadExpander implements CommentThreadExpanderInterface
{
    /**
     * @var \Spryker\Zed\CommentMerchantRelationshipConnector\Business\Reader\CommentReaderInterface
     */
    protected CommentReaderInterface $commentReader;

    /**
     * @param \Spryker\Zed\CommentMerchantRelationshipConnector\Business\Reader\CommentReaderInterface $commentReader
     */
    public function __construct(CommentReaderInterface $commentReader)
    {
        $this->commentReader = $commentReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function expandMerchantRelationshipCollection(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): MerchantRelationshipCollectionTransfer {
        $indexedCommentThreadTransfers = $this->commentReader->getCommentThreadsIndexedByOwnerId(
            CommentMerchantRelationshipConnectorConfig::COMMENT_THREAD_MERCHANT_RELATIONSHIP_OWNER_TYPE,
            $this->extractMerchantRelationshipIds($merchantRelationshipCollectionTransfer),
        );

        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $idMerchantRelationship = $merchantRelationshipTransfer->getIdMerchantRelationshipOrFail();
            $commentThreadTransfer = $indexedCommentThreadTransfers[$idMerchantRelationship] ?? null;

            if ($commentThreadTransfer) {
                $merchantRelationshipTransfer->setCommentThread($commentThreadTransfer);
            }
        }

        return $merchantRelationshipCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return list<int>
     */
    protected function extractMerchantRelationshipIds(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): array {
        $merchantRelationshipIds = [];
        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $merchantRelationshipIds[] = $merchantRelationshipTransfer->getIdMerchantRelationshipOrFail();
        }

        return array_unique($merchantRelationshipIds);
    }
}
