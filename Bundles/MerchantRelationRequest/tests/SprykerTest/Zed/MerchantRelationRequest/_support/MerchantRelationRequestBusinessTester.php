<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationRequest;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\PermissionCollectionBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery;
use Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestToCompanyBusinessUnitQuery;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery;
use Spryker\Client\MerchantRelationRequest\Plugin\Permission\CreateMerchantRelationRequestPermissionPlugin;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 * @method \Spryker\Zed\MerchantRelationRequest\Business\MerchantRelationRequestFacadeInterface getFacade(?string $moduleName = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\MerchantRelationRequest\PHPMD)
 */
class MerchantRelationRequestBusinessTester extends Actor
{
    use _generated\MerchantRelationRequestBusinessTesterActions;

    /**
     * @return void
     */
    public function ensureMerchantRelationRequestTablesAreEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getMerchantRelationRequestQuery());
    }

    /**
     * @return void
     */
    public function ensureMerchantRelationshipTablesAreEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getMerchantRelationshipQuery());
    }

    /**
     * @param string $status
     * @param array<mixed> $companySeed
     * @param array<mixed> $ownerCompanyBusinessUnitSeed
     * @param array<mixed> $assigneeCompanyBusinessUnitSeed
     * @param bool $withAssigneeCompanyBusinessUnits
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    public function createStatusSpecificRequest(
        string $status,
        array $companySeed = [],
        array $ownerCompanyBusinessUnitSeed = [],
        array $assigneeCompanyBusinessUnitSeed = [],
        bool $withAssigneeCompanyBusinessUnits = true
    ): MerchantRelationRequestTransfer {
        $merchantTransfer = $this->haveMerchant();
        $companyTransfer = $this->haveCompany(array_merge([CompanyTransfer::IS_ACTIVE => true], $companySeed));
        $companyUserTransfer = $this->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $companyUserTransfer->setCompany($companyTransfer);

        $ownerCompanyBusinessUnit = $this->haveCompanyBusinessUnit(array_merge([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ], $ownerCompanyBusinessUnitSeed));

        $merchantRelationRequestData = [
            MerchantRelationRequestTransfer::STATUS => $status,
            MerchantRelationRequestTransfer::MERCHANT => $merchantTransfer,
            MerchantRelationRequestTransfer::COMPANY_USER => $companyUserTransfer,
            MerchantRelationRequestTransfer::OWNER_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnit,
        ];

        if ($withAssigneeCompanyBusinessUnits) {
            $assigneeCompanyBusinessUnits = new ArrayObject([
                $this->haveCompanyBusinessUnit(array_merge([
                    CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
                ], $assigneeCompanyBusinessUnitSeed)),
                $this->haveCompanyBusinessUnit(array_merge([
                    CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
                ], $assigneeCompanyBusinessUnitSeed)),
            ]);

            $merchantRelationRequestData[MerchantRelationRequestTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS] = $assigneeCompanyBusinessUnits;
        }

        $merchantRelationRequest = $this->haveMerchantRelationRequest($merchantRelationRequestData);

        return $merchantRelationRequest
            ->setMerchant($merchantTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setOwnerCompanyBusinessUnit($ownerCompanyBusinessUnit);
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString(int $length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return void
     */
    public function assignCreateMerchantRelationRequestPermission(CompanyUserTransfer $companyUserTransfer): void
    {
        $permissionCollectionTransfer = (new PermissionCollectionBuilder())->build();
        $permissionCollectionTransfer->addPermission(
            $this->havePermission(new CreateMerchantRelationRequestPermissionPlugin()),
        );

        $companyRoleTransfer = $this->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyUserTransfer->getFkCompany(),
            CompanyRoleTransfer::PERMISSION_COLLECTION => $permissionCollectionTransfer,
        ]);

        $companyUserTransfer->setCompanyRoleCollection(
            (new CompanyRoleCollectionTransfer())->addRole($companyRoleTransfer),
        );

        $this->assignCompanyRolesToCompanyUser($companyUserTransfer);
    }

    /**
     * @param string|null $merchantRelationRequestUuid
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function createMerchantRelationship(?string $merchantRelationRequestUuid = null): MerchantRelationshipTransfer
    {
        $merchantTransfer = $this->haveMerchant();
        $companyTransfer = $this->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $ownerCompanyBusinessUnit = $this->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompanyOrFail(),
        ]);

        $assigneeCompanyBusinessUnits = new ArrayObject([
            $this->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompanyOrFail()]),
            $this->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompanyOrFail()]),
        ]);

        return $this->haveMerchantRelationship([
            MerchantRelationshipTransfer::MERCHANT_RELATION_REQUEST_UUID => $merchantRelationRequestUuid,
            MerchantRelationshipTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchantOrFail(),
            MerchantRelationshipTransfer::MERCHANT => $merchantTransfer,
            MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnit->getIdCompanyBusinessUnitOrFail(),
            MerchantRelationshipTransfer::OWNER_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnit,
            MerchantRelationshipTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS =>
                (new CompanyBusinessUnitCollectionTransfer())->setCompanyBusinessUnits($assigneeCompanyBusinessUnits),
        ]);
    }

    /**
     * @param array<mixed> $firstMerchantRelationRequestSeed
     * @param array<mixed> $secondMerchantRelationRequestSeed
     * @param array<mixed> $ownerCompanyBusinessUnitSeed
     *
     * @return list<\Generated\Shared\Transfer\MerchantRelationRequestTransfer>
     */
    public function createTwoMerchantRelationRequestsToSameMerchant(
        array $firstMerchantRelationRequestSeed = [],
        array $secondMerchantRelationRequestSeed = [],
        array $ownerCompanyBusinessUnitSeed = []
    ): array {
        $merchantTransfer = $this->haveMerchant();
        $companyTransfer = $this->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $companyUserTransfer = $this->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $ownerCompanyBusinessUnit1 = $this->haveCompanyBusinessUnit(array_merge([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ], $ownerCompanyBusinessUnitSeed));
        $assigneeCompanyBusinessUnits1 = new ArrayObject([
            $this->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany()]),
            $this->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany()]),
        ]);

        $ownerCompanyBusinessUnit2 = $this->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $assigneeCompanyBusinessUnits2 = new ArrayObject([
            $this->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany()]),
            $this->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany()]),
        ]);

        $merchantRelationRequestTransfer1 = $this->haveMerchantRelationRequest(array_merge([
            MerchantRelationRequestTransfer::MERCHANT => $merchantTransfer,
            MerchantRelationRequestTransfer::COMPANY_USER => $companyUserTransfer,
            MerchantRelationRequestTransfer::OWNER_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnit1,
            MerchantRelationRequestTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS => $assigneeCompanyBusinessUnits1,
        ], $firstMerchantRelationRequestSeed));

        $merchantRelationRequestTransfer2 = $this->haveMerchantRelationRequest(array_merge([
            MerchantRelationRequestTransfer::MERCHANT => $merchantTransfer,
            MerchantRelationRequestTransfer::COMPANY_USER => $companyUserTransfer,
            MerchantRelationRequestTransfer::OWNER_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnit2,
            MerchantRelationRequestTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS => $assigneeCompanyBusinessUnits2,
        ], $secondMerchantRelationRequestSeed));

        $merchantRelationRequestTransfer1
            ->setMerchant($merchantTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setOwnerCompanyBusinessUnit($ownerCompanyBusinessUnit1);

        $merchantRelationRequestTransfer2
            ->setMerchant($merchantTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setOwnerCompanyBusinessUnit($ownerCompanyBusinessUnit2);

        return [
            $merchantRelationRequestTransfer1,
            $merchantRelationRequestTransfer2,
        ];
    }

    /**
     * @return \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestQuery
     */
    public function getMerchantRelationRequestQuery(): SpyMerchantRelationRequestQuery
    {
        return SpyMerchantRelationRequestQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantRelationRequest\Persistence\SpyMerchantRelationRequestToCompanyBusinessUnitQuery
     */
    public function getMerchantRelationRequestToCompanyBusinessUnitQuery(): SpyMerchantRelationRequestToCompanyBusinessUnitQuery
    {
        return SpyMerchantRelationRequestToCompanyBusinessUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery
     */
    public function getMerchantRelationshipQuery(): SpyMerchantRelationshipQuery
    {
        return SpyMerchantRelationshipQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery
     */
    public function getMerchantRelationshipToCompanyBusinessUnitQuery(): SpyMerchantRelationshipToCompanyBusinessUnitQuery
    {
        return SpyMerchantRelationshipToCompanyBusinessUnitQuery::create();
    }
}
