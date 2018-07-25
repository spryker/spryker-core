<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;

class MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipFacadeBridge implements MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipFacadeInterface
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
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function getMerchantRelationshipByKey(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        return $this->merchantRelationshipFacade->getMerchantRelationshipByKey($merchantRelationshipTransfer);
    }
}
