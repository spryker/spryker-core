<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationshipConnector\Communication\Plugin\MerchantRelationship;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipExpanderPluginInterface;

/**
 * @method \Spryker\Zed\CommentMerchantRelationshipConnector\Business\CommentMerchantRelationshipConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\CommentMerchantRelationshipConnector\CommentMerchantRelationshipConnectorConfig getConfig()
 */
class CommentThreadMerchantRelationshipExpanderPlugin extends AbstractPlugin implements MerchantRelationshipExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `MerchantRelationshipTransfer.idMerchantRelationship` to be set.
     * - Finds comment thread for merchant relationship.
     * - Populates `MerchantRelationshipTransfer.commentThread` with found comment thread.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function expand(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        $merchantRelationshipCollectionTransfer = $this->getFacade()
            ->expandMerchantRelationshipCollectionWithCommentThread(
                (new MerchantRelationshipCollectionTransfer())->addMerchantRelationship($merchantRelationshipTransfer),
            );

        return $merchantRelationshipCollectionTransfer->getMerchantRelationships()->getIterator()->current();
    }
}
