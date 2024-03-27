<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationshipConnector\Business;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CommentMerchantRelationshipConnector\Business\CommentMerchantRelationshipConnectorBusinessFactory getFactory()
 */
class CommentMerchantRelationshipConnectorFacade extends AbstractFacade implements CommentMerchantRelationshipConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function expandMerchantRelationshipCollectionWithCommentThread(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
    ): MerchantRelationshipCollectionTransfer {
        return $this->getFactory()
            ->createCommentThreadExpander()
            ->expandMerchantRelationshipCollection($merchantRelationshipCollectionTransfer);
    }
}
