<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationRequestConnector\Business;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;

/**
 * @method \Spryker\Zed\CommentMerchantRelationRequestConnector\Business\CommentMerchantRelationRequestConnectorBusinessFactory getFactory()
 */
interface CommentMerchantRelationRequestConnectorFacadeInterface
{
    /**
     * Specification:
     * - Expects `MerchantRelationRequestCollectionTransfer.merchantRelationRequests` to be provided.
     * - Requires `MerchantRelationRequestTransfer.idMerchantRelationRequest` to be set.
     * - Finds comment thread for each merchant relation request.
     * - Populates `MerchantRelationRequestTransfer.commentThread` with found comment thread.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function expandMerchantRelationRequestCollectionWithCommentThread(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer;

    /**
     * Specification:
     * - Expects `MerchantRelationshipCollectionTransfer.merchantRelationships` to be provided.
     * - Expects `MerchantRelationshipTransfer.merchantRelationRequestUuid` to be provided.
     * - Requires `MerchantRelationshipTransfer.idMerchantRelationship` to be set.
     * - Finds merchant relation requests by provided UUIDs.
     * - Filters out merchant relation requests without comment thread.
     * - Copies comment threads from found merchant relation requests to merchant relationships.
     * - Populates `MerchantRelationshipTransfer.commentThread` with copied comment thread.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function copyCommentThreadsFromMerchantRelationRequests(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): MerchantRelationshipCollectionTransfer;
}
