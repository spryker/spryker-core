<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Reader;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface;

class MerchantRelationshipReader implements MerchantRelationshipReaderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface
     */
    protected $merchantRelationshipFacade;

    /**
     * @param \Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Dependency\Facade\PriceProductMerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade
     */
    public function __construct(PriceProductMerchantRelationshipMerchantPortalGuiToMerchantRelationshipFacadeInterface $merchantRelationshipFacade)
    {
        $this->merchantRelationshipFacade = $merchantRelationshipFacade;
    }

    /**
     * @param int $idMerchantRelationship
     *
     * @return string|null
     */
    public function findMerchantRelationshipNameByIdMerchantRelationship(int $idMerchantRelationship): ?string
    {
        $merchantRelationshipTransfer = $this->merchantRelationshipFacade->findMerchantRelationshipById(
            (new MerchantRelationshipTransfer())->setIdMerchantRelationship($idMerchantRelationship),
        );

        if (!$merchantRelationshipTransfer || !$merchantRelationshipTransfer->getOwnerCompanyBusinessUnit()) {
            return null;
        }

        return sprintf(
            '%s - %s',
            $idMerchantRelationship,
            $merchantRelationshipTransfer->getOwnerCompanyBusinessUnitOrFail()->getNameOrFail(),
        );
    }
}
