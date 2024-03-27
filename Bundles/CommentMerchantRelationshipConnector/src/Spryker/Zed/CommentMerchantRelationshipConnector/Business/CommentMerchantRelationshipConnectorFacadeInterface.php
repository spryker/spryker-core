<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationshipConnector\Business;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;

/**
 * @method \Spryker\Zed\CommentMerchantRelationshipConnector\Business\CommentMerchantRelationshipConnectorBusinessFactory getFactory()
 */
interface CommentMerchantRelationshipConnectorFacadeInterface
{
    /**
     * Specification:
     * - Expects `MerchantRelationshipCollectionTransfer.merchantRelationships` to be provided.
     * - Requires `MerchantRelationshipTransfer.idMerchantRelationship` to be set.
     * - Finds comment thread for each merchant relationship.
     * - Populates `MerchantRelationshipTransfer.commentThread` with found comment thread.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function expandMerchantRelationshipCollectionWithCommentThread(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): MerchantRelationshipCollectionTransfer;
}
