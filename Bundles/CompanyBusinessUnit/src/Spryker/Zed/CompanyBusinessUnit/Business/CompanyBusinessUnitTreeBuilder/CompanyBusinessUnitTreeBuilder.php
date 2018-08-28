<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitTreeBuilder;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTreeItemTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTreeTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;

class CompanyBusinessUnitTreeBuilder implements CompanyBusinessUnitTreeBuilderInterface
{
    protected const FK_PARENT_COMPANY_BUSINESS_UNIT_KEY = 'fk_parent_company_business_unit';
    protected const ID_COMPANY_BUSINESS_UNIT_KEY = 'id_company_business_unit';
    protected const LEVEL_KEY = 'level';
    protected const CHILDREN_KEY = 'children';

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
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTreeTransfer
     */
    public function getCustomerCompanyBusinessUnitTree(CustomerTransfer $customerTransfer): CompanyBusinessUnitTreeTransfer
    {
        if ($customerTransfer->getCompanyUserTransfer() === null) {
            return new CompanyBusinessUnitTreeTransfer();
        }

        $idCompany = $customerTransfer->getCompanyUserTransfer()->getFkCompany();

        $companyBusinessUnits = $this->getCompanyBusinessUnitCollection($idCompany);

        $companyBusinessUnitTree = $this->buildTree($companyBusinessUnits->getCompanyBusinessUnits());

        return $companyBusinessUnitTree;
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
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTreeTransfer
     */
    protected function buildTree(ArrayObject $companyBusinessUnits, ?int $idParentCompanyBusinessUnit = null, int $indent = 0): CompanyBusinessUnitTreeTransfer
    {
        $companyBusinessUnitTree = new CompanyBusinessUnitTreeTransfer();
        $companyBusinessUnitTreeItems = new ArrayObject();
        foreach ($companyBusinessUnits as $companyBusinessUnit) {
            $companyBusinessUnitArray = $companyBusinessUnit->toArray();
            if ($companyBusinessUnitArray[static::FK_PARENT_COMPANY_BUSINESS_UNIT_KEY] === $idParentCompanyBusinessUnit) {
                $companyBusinessUnitArray[static::CHILDREN_KEY] = [];
                $companyBusinessUnitArray[static::LEVEL_KEY] = $indent;
                $children = $this->buildTree($companyBusinessUnits, $companyBusinessUnitArray[static::ID_COMPANY_BUSINESS_UNIT_KEY], $indent++);
                $companyBusinessUnitArray[static::CHILDREN_KEY] = $children;

                $companyBusinessUnitTreeItem = new CompanyBusinessUnitTreeItemTransfer();
                $this->hydrateCompanyBusinessUnitTreeItemTransfer($companyBusinessUnitTreeItem, $companyBusinessUnitArray);
                $companyBusinessUnitTreeItems[] = $companyBusinessUnitTreeItem;
            }
        }
        $companyBusinessUnitTree->setCompanyBusinessUnitTreeItems($companyBusinessUnitTreeItems);

        return $companyBusinessUnitTree;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTreeItemTransfer $companyBusinessUnitTreeItem
     * @param array $companyBusinessUnitArray
     *
     * @return void
     */
    protected function hydrateCompanyBusinessUnitTreeItemTransfer(
        CompanyBusinessUnitTreeItemTransfer $companyBusinessUnitTreeItem,
        array $companyBusinessUnitArray
    ): void {
        $companyBusinessUnitTreeItem->setFkParentCompanyBusinessUnit($companyBusinessUnitArray[static::FK_PARENT_COMPANY_BUSINESS_UNIT_KEY]);
        $companyBusinessUnitTreeItem->setChildren($companyBusinessUnitArray[static::CHILDREN_KEY]);
        $companyBusinessUnitTreeItem->setLevel($companyBusinessUnitArray[static::LEVEL_KEY]);
        $companyBusinessUnitTreeItem->setIdCompanyBusinessUnit($companyBusinessUnitArray[static::ID_COMPANY_BUSINESS_UNIT_KEY]);

        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->fromArray($companyBusinessUnitArray, true);

        $companyBusinessUnitTreeItem->setCompanyBusinessUnit($companyBusinessUnitTransfer);
    }
}
