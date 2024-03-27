<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationRequestConnector\Communication\Plugin\MerchantRelationship;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\CommentMerchantRelationRequestConnector\Business\CommentMerchantRelationRequestConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CommentMerchantRelationRequestConnector\CommentMerchantRelationRequestConnectorConfig getConfig()
 */
class CopyCommentThreadToMerchantRelationshipPostCreatePlugin extends AbstractPlugin implements MerchantRelationshipPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Expects `MerchantRelationshipTransfer.merchantRelationRequestUuid` to be provided.
     * - Requires `MerchantRelationshipTransfer.idMerchantRelationship` to be set.
     * - Finds merchant relation request by provided UUID.
     * - Filters out merchant relation request without comment thread.
     * - Copies comment thread from found merchant relation request to merchant relationship.
     * - Populates `MerchantRelationshipTransfer.commentThread` with copied comment thread.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function execute(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationshipCollectionTransfer = $this
            ->getFacade()
            ->copyCommentThreadsFromMerchantRelationRequests(
                (new MerchantRelationshipCollectionTransfer())->addMerchantRelationship($merchantRelationshipTransfer),
            );

        return $merchantRelationshipCollectionTransfer->getMerchantRelationships()->getIterator()->current();
    }
}
