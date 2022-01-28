<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiPaginationTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiValidationErrorTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\MerchantRelationshipApiTransfer;
use Generated\Shared\Transfer\MerchantRelationshipBusinessUnitApiTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipProductListApiTransfer;
use Generated\Shared\Transfer\MerchantRelationshipResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortCollectionTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\MerchantRelationshipApi\Business\Request\MerchantRelationshipRequestDataInterface;
use Spryker\Zed\MerchantRelationshipApi\Dependency\Service\MerchantRelationshipApiToUtilEncodingServiceInterface;

class MerchantRelationshipMapper implements MerchantRelationshipMapperInterface
{
    /**
     * @var string
     */
    protected const SORT_DIRECTION_ASCENDING = 'ASC';

    /**
     * @var \Spryker\Zed\MerchantRelationshipApi\Dependency\Service\MerchantRelationshipApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\MerchantRelationshipApi\Dependency\Service\MerchantRelationshipApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(MerchantRelationshipApiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    public function mapApiRequestTransferToMerchantRelationshipCriteriaTransfer(
        ApiRequestTransfer $apiRequestTransfer,
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): MerchantRelationshipCriteriaTransfer {
        $apiFilterTransfer = $apiRequestTransfer->getFilter();

        if ($apiFilterTransfer === null) {
            return $merchantRelationshipCriteriaTransfer;
        }

        $json = $apiFilterTransfer->getCriteriaJson();
        if ($json !== null) {
            $criteriaFromRequest = (array)$this->utilEncodingService->decodeJson($json, true);
            $companyIds = isset($criteriaFromRequest[MerchantRelationshipRequestDataInterface::KEY_ID_COMPANY])
                ? [$criteriaFromRequest[MerchantRelationshipRequestDataInterface::KEY_ID_COMPANY]]
                : [];
            $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
                ->fromArray($criteriaFromRequest, true)
                ->setCompanyIds($companyIds);

            $merchantRelationshipCriteriaTransfer->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);
        }

        $sortByFields = $apiFilterTransfer->getSort();
        $sortCollectionTransfer = $this->mapSortDataToSortCollectionTransfer($sortByFields, new SortCollectionTransfer());

        $paginationTransfer = ($merchantRelationshipCriteriaTransfer->getPagination() ?? new PaginationTransfer())
            ->setMaxPerPage($apiFilterTransfer->getLimit())
            ->setFirstIndex($apiFilterTransfer->getOffset());

        return $merchantRelationshipCriteriaTransfer
            ->setSortCollection($sortCollectionTransfer)
            ->setPagination($paginationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param \Generated\Shared\Transfer\ApiPaginationTransfer $apiPaginationTransfer
     *
     * @return \Generated\Shared\Transfer\ApiPaginationTransfer
     */
    public function mapPaginationTransferToApiPaginationTransfer(
        PaginationTransfer $paginationTransfer,
        ApiPaginationTransfer $apiPaginationTransfer
    ): ApiPaginationTransfer {
        return $apiPaginationTransfer
            ->setPage($paginationTransfer->getPage())
            ->setFirst((string)$paginationTransfer->getFirstPage())
            ->setLast((string)$paginationTransfer->getLastPage())
            ->setNext((string)$paginationTransfer->getNextPage())
            ->setPrev((string)$paginationTransfer->getPreviousPage())
            ->setTotal($paginationTransfer->getNbResults())
            ->setPageTotal((int)ceil(
                $paginationTransfer->getNbResultsOrFail() /
                $paginationTransfer->getMaxPerPageOrFail(),
            ))
            ->setItemsPerPage($paginationTransfer->getMaxPerPage());
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function mapApiDataTransferToMerchantRelationshipTransfer(
        ApiDataTransfer $apiDataTransfer,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        $merchantRelationshipTransfer = $this->mapApiDataTransferBusinessUnitOwnerDataToMerchantRelationshipTransfer(
            $apiDataTransfer,
            $merchantRelationshipTransfer,
        );

        $merchantRelationshipTransfer = $this->mapApiDataTransferMerchantReferenceDataToMerchantRelationshipTransfer(
            $apiDataTransfer,
            $merchantRelationshipTransfer,
        );

        $merchantRelationshipTransfer = $this->mapApiDataTransferAssignedBusinessUnitsDataToMerchantRelationshipTransfer(
            $apiDataTransfer,
            $merchantRelationshipTransfer,
        );

        $merchantRelationshipTransfer = $this->mapApiDataTransferProductListsDataToMerchantRelationshipTransfer(
            $apiDataTransfer,
            $merchantRelationshipTransfer,
        );

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer
     * @param \Generated\Shared\Transfer\ApiCollectionTransfer $apiCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function mapMerchantRelationshipCollectionTransferToApiCollectionTransfer(
        MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer,
        ApiCollectionTransfer $apiCollectionTransfer
    ): ApiCollectionTransfer {
        $data = [];
        foreach ($merchantRelationshipCollectionTransfer->getMerchantRelationships() as $merchantRelationshipTransfer) {
            $merchantRelationshipApiTransfer = $this->mapMerchantRelationshipTransferToMerchantRelationshipApiTransfer(
                $merchantRelationshipTransfer,
                new MerchantRelationshipApiTransfer(),
            );

            $data[] = $merchantRelationshipApiTransfer->toArray(true, true);
        }

        $apiCollectionTransfer->setData($data);

        $apiPaginationTransfer = new ApiPaginationTransfer();
        $paginationTransfer = $merchantRelationshipCollectionTransfer->getPagination();
        if ($paginationTransfer !== null) {
            $apiPaginationTransfer = $this->mapPaginationTransferToApiPaginationTransfer(
                $paginationTransfer,
                $apiPaginationTransfer,
            );
        }

        return $apiCollectionTransfer->setPagination($apiPaginationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipApiTransfer $merchantRelationshipApiTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipApiTransfer
     */
    public function mapMerchantRelationshipTransferToMerchantRelationshipApiTransfer(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipApiTransfer $merchantRelationshipApiTransfer
    ): MerchantRelationshipApiTransfer {
        $merchantRelationshipApiTransfer->setIdMerchantRelationship($merchantRelationshipTransfer->getIdMerchantRelationship());

        $merchantRelationshipApiTransfer = $this->mapMerchantRelationshipOwnerCompanyBusinessUnitDataToMerchantRelationshipApiTransfer($merchantRelationshipTransfer, $merchantRelationshipApiTransfer);
        $merchantRelationshipApiTransfer = $this->mapMerchantRelationshipTransferMerchantDataToMerchantRelationshipApiTransfer($merchantRelationshipTransfer, $merchantRelationshipApiTransfer);
        $merchantRelationshipApiTransfer = $this->mapMerchantRelationshipTransferCompanyBusinessUnitCollectionToMerchantRelationshipApiTransfer($merchantRelationshipTransfer, $merchantRelationshipApiTransfer);
        $merchantRelationshipApiTransfer = $this->mapMerchantRelationshipTransferProductListDataToMerchantRelationshipApiTransfer($merchantRelationshipTransfer, $merchantRelationshipApiTransfer);

        return $merchantRelationshipApiTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipBusinessUnitApiTransfer $merchantRelationshipBusinessUnitApiTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipBusinessUnitApiTransfer
     */
    public function mapCompanyBusinessUnitTransferToMerchantRelationshipBusinessUnitApiTransfer(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer,
        MerchantRelationshipBusinessUnitApiTransfer $merchantRelationshipBusinessUnitApiTransfer
    ): MerchantRelationshipBusinessUnitApiTransfer {
        return $merchantRelationshipBusinessUnitApiTransfer
            ->setName($companyBusinessUnitTransfer->getName())
            ->setIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipResponseTransfer $merchantRelationshipResponseTransfer
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiValidationErrorTransfer> $apiValidationErrorTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    public function mapMerchantRelationshipResponseTransferToApiValidationErrorTransfers(
        MerchantRelationshipResponseTransfer $merchantRelationshipResponseTransfer,
        ArrayObject $apiValidationErrorTransfers
    ): ArrayObject {
        $preparedValidationErrors = [];
        foreach ($merchantRelationshipResponseTransfer->getErrors() as $merchantRelationshipErrorTransfer) {
            $preparedValidationErrors[$merchantRelationshipErrorTransfer->getField()][] = $merchantRelationshipErrorTransfer;
        }

        foreach ($preparedValidationErrors as $merchantRelationshipErrorTransfers) {
            $apiValidationErrorTransfer = new ApiValidationErrorTransfer();

            foreach ($merchantRelationshipErrorTransfers as $merchantRelationshipErrorTransfer) {
                $apiValidationErrorTransfer
                    ->setField($merchantRelationshipErrorTransfer->getField())
                    ->addMessages($merchantRelationshipErrorTransfer->getMessage());
            }

            $apiValidationErrorTransfers->append($apiValidationErrorTransfer);
        }

        return $apiValidationErrorTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipApiTransfer $merchantRelationshipApiTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipApiTransfer
     */
    protected function mapMerchantRelationshipOwnerCompanyBusinessUnitDataToMerchantRelationshipApiTransfer(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipApiTransfer $merchantRelationshipApiTransfer
    ): MerchantRelationshipApiTransfer {
        $companyBusinessUnit = $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit();
        if ($companyBusinessUnit === null) {
            return $merchantRelationshipApiTransfer;
        }
        $merchantRelationshipApiTransfer->setIdBusinessUnitOwner($companyBusinessUnit->getIdCompanyBusinessUnit());
        $merchantRelationshipApiTransfer->setBusinessUnitOwnerName($companyBusinessUnit->getName());

        $companyTransfer = $companyBusinessUnit->getCompany();
        if ($companyTransfer !== null) {
            $merchantRelationshipApiTransfer->setIdCompany($companyTransfer->getIdCompany());
            $merchantRelationshipApiTransfer->setCompanyName($companyTransfer->getName());
        }

        return $merchantRelationshipApiTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipApiTransfer $merchantRelationshipApiTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipApiTransfer
     */
    protected function mapMerchantRelationshipTransferCompanyBusinessUnitCollectionToMerchantRelationshipApiTransfer(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipApiTransfer $merchantRelationshipApiTransfer
    ): MerchantRelationshipApiTransfer {
        $companyBusinessUnitCollectionTransfer = $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits();
        if ($companyBusinessUnitCollectionTransfer === null) {
            return $merchantRelationshipApiTransfer;
        }

        $assignedBusinessUnits = new ArrayObject();
        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $assignedBusinessUnits->append($this->mapCompanyBusinessUnitTransferToMerchantRelationshipBusinessUnitApiTransfer(
                $companyBusinessUnitTransfer,
                new MerchantRelationshipBusinessUnitApiTransfer(),
            ));
        }

        return $merchantRelationshipApiTransfer->setAssignedBusinessUnits($assignedBusinessUnits);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipApiTransfer $merchantRelationshipApiTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipApiTransfer
     */
    protected function mapMerchantRelationshipTransferMerchantDataToMerchantRelationshipApiTransfer(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipApiTransfer $merchantRelationshipApiTransfer
    ): MerchantRelationshipApiTransfer {
        $merchant = $merchantRelationshipTransfer->getMerchant();
        if ($merchant === null) {
            return $merchantRelationshipApiTransfer;
        }

        $merchantRelationshipApiTransfer->setMerchantName($merchant->getName());
        $merchantRelationshipApiTransfer->setMerchantReference($merchant->getMerchantReference());

        return $merchantRelationshipApiTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipApiTransfer $merchantRelationshipApiTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipApiTransfer
     */
    protected function mapMerchantRelationshipTransferProductListDataToMerchantRelationshipApiTransfer(
        MerchantRelationshipTransfer $merchantRelationshipTransfer,
        MerchantRelationshipApiTransfer $merchantRelationshipApiTransfer
    ): MerchantRelationshipApiTransfer {
        $productListTransfers = new ArrayObject();
        foreach ($merchantRelationshipTransfer->getProductLists() as $productListTransfer) {
            $productListTransfers->append(
                (new MerchantRelationshipProductListApiTransfer())
                    ->setIdProductList($productListTransfer->getIdProductList())
                    ->setName($productListTransfer->getTitle()),
            );
        }

        return $merchantRelationshipApiTransfer->setAssignedProductLists($productListTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function mapApiDataTransferMerchantReferenceDataToMerchantRelationshipTransfer(
        ApiDataTransfer $apiDataTransfer,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        $apiData = $apiDataTransfer->getData();
        $merchantReference = $apiData[MerchantRelationshipRequestDataInterface::KEY_MERCHANT_REFERENCE] ?? null;

        if ($merchantReference !== null) {
            $merchantRelationshipTransfer->setMerchant(
                (new MerchantTransfer())->setMerchantReference($merchantReference),
            );
        }

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function mapApiDataTransferBusinessUnitOwnerDataToMerchantRelationshipTransfer(
        ApiDataTransfer $apiDataTransfer,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        $apiData = $apiDataTransfer->getData();
        $idBusinessUnitOwner = $apiData[MerchantRelationshipRequestDataInterface::KEY_ID_BUSINESS_UNIT_OWNER] ?? null;

        if ($idBusinessUnitOwner !== null) {
            $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
                ->setIdCompanyBusinessUnit($idBusinessUnitOwner);

            $idCompany = $apiData[MerchantRelationshipRequestDataInterface::KEY_ID_COMPANY] ?? null;
            if ($idCompany !== null) {
                $companyBusinessUnitTransfer->setCompany((new CompanyTransfer())->setIdCompany($idCompany));
            }

            $merchantRelationshipTransfer
                ->setFkCompanyBusinessUnit($idBusinessUnitOwner)
                ->setOwnerCompanyBusinessUnit($companyBusinessUnitTransfer);
        }

        return $merchantRelationshipTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function mapApiDataTransferAssignedBusinessUnitsDataToMerchantRelationshipTransfer(
        ApiDataTransfer $apiDataTransfer,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        $apiData = $apiDataTransfer->getData();
        $assignedBusinessUnits = $apiData[MerchantRelationshipRequestDataInterface::KEY_ASSIGNED_BUSINESS_UNITS] ?? null;

        if ($assignedBusinessUnits === null) {
            return $merchantRelationshipTransfer;
        }

        $companyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();
        $assigneeCompanyBusinessUnitsCollection = $merchantRelationshipTransfer->getAssigneeCompanyBusinessUnits();
        foreach ($assignedBusinessUnits as $assignedBusinessUnit) {
            $idCompanyBusinessUnit = $assignedBusinessUnit[MerchantRelationshipRequestDataInterface::KEY_ID_COMPANY_BUSINESS_UNIT] ?? null;
            if ($idCompanyBusinessUnit === null) {
                continue;
            }

            $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($idCompanyBusinessUnit);
            if ($assigneeCompanyBusinessUnitsCollection !== null) {
                $companyBusinessUnitTransfer = $this->findCompanyBusinessUnitTransferInAssigneeCompanyBusinessUnitsCollection(
                    $idCompanyBusinessUnit,
                    $assigneeCompanyBusinessUnitsCollection,
                ) ?? $companyBusinessUnitTransfer;
            }

            $companyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($companyBusinessUnitTransfer);
        }

        return $merchantRelationshipTransfer->setAssigneeCompanyBusinessUnits($companyBusinessUnitCollectionTransfer);
    }

    /**
     * @param int $idCompanyBusinessUnit
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null
     */
    protected function findCompanyBusinessUnitTransferInAssigneeCompanyBusinessUnitsCollection(
        int $idCompanyBusinessUnit,
        CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer
    ): ?CompanyBusinessUnitTransfer {
        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            if ($companyBusinessUnitTransfer->getIdCompanyBusinessUnit() === $idCompanyBusinessUnit) {
                return $companyBusinessUnitTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    protected function mapApiDataTransferProductListsDataToMerchantRelationshipTransfer(
        ApiDataTransfer $apiDataTransfer,
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): MerchantRelationshipTransfer {
        $apiData = $apiDataTransfer->getData();
        $assignedProductLists = $apiData[MerchantRelationshipRequestDataInterface::KEY_ASSIGNED_PRODUCT_LISTS] ?? null;

        if ($assignedProductLists === null) {
            return $merchantRelationshipTransfer;
        }

        $merchantRelationshipTransfer->setProductListIds([]);
        foreach ($assignedProductLists as $assignedProductList) {
            $idProductList = $assignedProductList[MerchantRelationshipRequestDataInterface::KEY_ID_PRODUCT_LIST] ?? null;
            if ($idProductList !== null) {
                $merchantRelationshipTransfer->addProductListId($idProductList);
            }
        }

        return $merchantRelationshipTransfer;
    }

    /**
     * @param array<string, string> $sortByFields
     * @param \Generated\Shared\Transfer\SortCollectionTransfer $sortCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SortCollectionTransfer
     */
    protected function mapSortDataToSortCollectionTransfer(array $sortByFields, SortCollectionTransfer $sortCollectionTransfer): SortCollectionTransfer
    {
        foreach ($sortByFields as $sortField => $direction) {
            $isAscending = $direction === static::SORT_DIRECTION_ASCENDING || $direction === '';
            $sortTransfer = (new SortTransfer())
                ->setField($sortField)
                ->setIsAscending($isAscending);
            $sortCollectionTransfer->addSort($sortTransfer);
        }

        return $sortCollectionTransfer;
    }
}
