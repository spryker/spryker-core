<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationRequestConnector\Dependency\Facade;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;

class CommentMerchantRelationRequestConnectorToMerchantRelationRequestFacadeBridge implements CommentMerchantRelationRequestConnectorToMerchantRelationRequestFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Business\MerchantRelationRequestFacadeInterface
     */
    protected $merchantRelationRequestFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Business\MerchantRelationRequestFacadeInterface $merchantRelationRequestFacade
     */
    public function __construct($merchantRelationRequestFacade)
    {
        $this->merchantRelationRequestFacade = $merchantRelationRequestFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function getMerchantRelationRequestCollection(
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCollectionTransfer {
        return $this->merchantRelationRequestFacade
            ->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);
    }
}
