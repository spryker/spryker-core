<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Dependency\Facade;

use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

class MerchantRelationshipProductListGuiToMerchantRelationshipFacadeBridge implements MerchantRelationshipProductListGuiToMerchantRelationshipFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Business\MerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct($merchantRelationshipFacade)
    {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer[]
     */
    public function getMerchantRelationshipCollection(MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer): array
    {
        return $this->merchantRelationshipFacade->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function getMerchantRelationshipById(MerchantRelationshipTransfer $merchantRelationshipTransfer): MerchantRelationshipTransfer
    {
        return $this->merchantRelationshipFacade->getMerchantRelationshipById($merchantRelationshipTransfer);
    }
}
