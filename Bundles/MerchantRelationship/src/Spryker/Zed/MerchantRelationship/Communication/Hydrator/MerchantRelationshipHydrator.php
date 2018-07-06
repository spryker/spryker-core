<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Communication\Hydrator;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface;

class MerchantRelationshipHydrator implements MerchantRelationshipHydratorInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface
     */
    protected $merchantRelationshipRepository;

    /**
     * @param \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface $merchantRelationshipRepository
     */
    public function __construct(
        MerchantRelationshipRepositoryInterface $merchantRelationshipRepository
    ) {
        $this->merchantRelationshipRepository = $merchantRelationshipRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function hydrate(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        if ($companyUserTransfer->getCompanyBusinessUnit()) {
            $merchantRelationships = $this->merchantRelationshipRepository->getAssignedMerchantRelationshipsByIdCompanyBusinessUnit(
                $companyUserTransfer->getFkCompanyBusinessUnit()
            );

            $companyUserTransfer->getCompanyBusinessUnit()
                ->setMerchantRelationships(new ArrayObject($merchantRelationships));
        }

        return $companyUserTransfer;
    }
}
