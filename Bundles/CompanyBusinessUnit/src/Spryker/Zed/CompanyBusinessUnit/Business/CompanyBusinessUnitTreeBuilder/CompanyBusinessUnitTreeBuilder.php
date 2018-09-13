<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitTreeBuilder;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTreeNodeCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTreeNodeTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;

class CompanyBusinessUnitTreeBuilder implements CompanyBusinessUnitTreeBuilderInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface
     */
    protected $companyBusinessUnitRepository;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository
     */
    public function __construct(CompanyBusinessUnitRepositoryInterface $companyBusinessUnitRepository)
    {
        $this->companyBusinessUnitRepository = $companyBusinessUnitRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTreeNodeCollectionTransfer
     */
    public function getCustomerCompanyBusinessUnitTree(CustomerTransfer $customerTransfer): CompanyBusinessUnitTreeNodeCollectionTransfer
    {
        if ($customerTransfer->getCompanyUserTransfer() === null) {
            return new CompanyBusinessUnitTreeNodeCollectionTransfer();
        }

        $idCompany = $customerTransfer->getCompanyUserTransfer()->getFkCompany();
        $companyBusinessUnits = $this->getCompanyBusinessUnitCollection($idCompany);
        $companyBusinessUnitTreeNodes = $this->buildTree($companyBusinessUnits->getCompanyBusinessUnits(), null, 0);

        return (new CompanyBusinessUnitTreeNodeCollectionTransfer())->setCompanyBusinessUnitTreeNodes($companyBusinessUnitTreeNodes);
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function getCompanyBusinessUnitCollection(int $idCompany): CompanyBusinessUnitCollectionTransfer
    {
        $criteriaFilterTransfer = new CompanyBusinessUnitCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompany($idCompany);

        return $this->companyBusinessUnitRepository->getCompanyBusinessUnitCollection($criteriaFilterTransfer);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CompanyBusinessUnitTransfer[] $companyBusinessUnits
     * @param int|null $idParentCompanyBusinessUnit
     * @param int $indent
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTreeNodeTransfer[]|\ArrayObject
     */
    protected function buildTree(ArrayObject $companyBusinessUnits, ?int $idParentCompanyBusinessUnit, int $indent): ArrayObject
    {
        $companyBusinessUnitTreeNodes = [];
        foreach ($companyBusinessUnits as $companyBusinessUnit) {
            if ($companyBusinessUnit->getFkParentCompanyBusinessUnit() !== $idParentCompanyBusinessUnit) {
                continue;
            }

            $children = $this->buildTree($companyBusinessUnits, $companyBusinessUnit->getIdCompanyBusinessUnit(), $indent + 1);

            $companyBusinessUnitTreeNodes[] = $this
                ->createEmptyTreeNode()
                ->setLevel($indent)
                ->setCompanyBusinessUnit($companyBusinessUnit)
                ->setChildren($children);
        }

        return new ArrayObject($companyBusinessUnitTreeNodes);
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTreeNodeTransfer
     */
    protected function createEmptyTreeNode(): CompanyBusinessUnitTreeNodeTransfer
    {
        return (new CompanyBusinessUnitTreeNodeTransfer())
            ->setChildren(new ArrayObject());
    }
}
