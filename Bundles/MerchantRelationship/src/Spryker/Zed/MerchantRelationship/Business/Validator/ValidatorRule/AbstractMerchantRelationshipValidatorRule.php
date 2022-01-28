<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business\Validator\ValidatorRule;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantRelationshipErrorTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

abstract class AbstractMerchantRelationshipValidatorRule implements MerchantRelationshipValidatorRuleInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface
     */
    protected $merchantRelationshipRepository;

    /**
     * @var \Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var array<\Generated\Shared\Transfer\MerchantRelationshipTransfer>
     */
    protected static $merchantRelationshipTransfers;

    /**
     * @var array<\Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer>
     */
    protected static $companyBusinessUnitCollectionTransfers;

    /**
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer|null
     */
    protected function findMerchantRelationship(int $idMerchantRelationship): ?MerchantRelationshipTransfer
    {
        if (isset(static::$merchantRelationshipTransfers[$idMerchantRelationship])) {
            return static::$merchantRelationshipTransfers[$idMerchantRelationship];
        }

        $merchantRelationshipTransfer = $this->merchantRelationshipRepository->getMerchantRelationshipById(
            $idMerchantRelationship,
        );

        if (!$merchantRelationshipTransfer) {
            return null;
        }

        static::$merchantRelationshipTransfers[$idMerchantRelationship] = $merchantRelationshipTransfer;

        return static::$merchantRelationshipTransfers[$idMerchantRelationship];
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function getCompanyBusinessUnitCollection(int $idCompany): CompanyBusinessUnitCollectionTransfer
    {
        if (isset(static::$companyBusinessUnitCollectionTransfers[$idCompany])) {
            return static::$companyBusinessUnitCollectionTransfers[$idCompany];
        }

        static::$companyBusinessUnitCollectionTransfers[$idCompany] = $this->companyBusinessUnitFacade->getCompanyBusinessUnitCollection(
            $this->createCompanyBusinessUnitCriteriaFilterTransfer($idCompany),
        );

        return static::$companyBusinessUnitCollectionTransfers[$idCompany];
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer
     */
    protected function createCompanyBusinessUnitCriteriaFilterTransfer(int $idCompany): CompanyBusinessUnitCriteriaFilterTransfer
    {
        return (new CompanyBusinessUnitCriteriaFilterTransfer())->setIdCompany($idCompany);
    }

    /**
     * @param string $field
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipErrorTransfer
     */
    protected function createMerchantRelationshipErrorTransfer(string $field, string $message): MerchantRelationshipErrorTransfer
    {
        return (new MerchantRelationshipErrorTransfer())
            ->setField($field)
            ->setMessage($message);
    }
}
