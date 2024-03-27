<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationRequestConnector\Communication\Plugin\MerchantRelationRequest;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CommentMerchantRelationRequestConnector\Business\CommentMerchantRelationRequestConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CommentMerchantRelationRequestConnector\CommentMerchantRelationRequestConnectorConfig getConfig()
 */
class CommentThreadMerchantRelationRequestExpanderPlugin extends AbstractPlugin implements MerchantRelationRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
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
    public function expand(
        MerchantRelationRequestCollectionTransfer $merchantRelationRequestCollectionTransfer
    ): MerchantRelationRequestCollectionTransfer {
        return $this
            ->getFacade()
            ->expandMerchantRelationRequestCollectionWithCommentThread($merchantRelationRequestCollectionTransfer);
    }
}
