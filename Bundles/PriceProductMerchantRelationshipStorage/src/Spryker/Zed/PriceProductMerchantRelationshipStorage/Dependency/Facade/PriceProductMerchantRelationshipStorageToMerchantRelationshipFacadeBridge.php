<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade;

use Generated\Shared\Transfer\MerchantRelationshipFilterTransfer;

class PriceProductMerchantRelationshipStorageToMerchantRelationshipFacadeBridge implements PriceProductMerchantRelationshipStorageToMerchantRelationshipFacadeInterface
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
     * @param \Generated\Shared\Transfer\MerchantRelationshipFilterTransfer|null $merchantRelationshipFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
     */
    public function getMerchantRelationshipCollection(?MerchantRelationshipFilterTransfer $merchantRelationshipFilterTransfer = null): array
    {
        return $this->merchantRelationshipFacade->getMerchantRelationshipCollection($merchantRelationshipFilterTransfer);
    }
}
