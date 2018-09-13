<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;

class MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipFacadeBridge implements MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipFacadeInterface
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
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    public function findMerchantRelationshipByKey(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): ?MerchantRelationshipTransfer {
        return $this->merchantRelationshipFacade->findMerchantRelationshipByKey($merchantRelationshipTransfer);
    }
}
