<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CommentMerchantRelationRequestConnector;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;

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
 * @method \Spryker\Zed\CommentMerchantRelationRequestConnector\Business\CommentMerchantRelationRequestConnectorFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\Zed\CommentMerchantRelationRequestConnector\PHPMD)
 */
class CommentMerchantRelationRequestConnectorBusinessTester extends Actor
{
    use _generated\CommentMerchantRelationRequestConnectorBusinessTesterActions;

    /**
     * @uses \Spryker\Zed\CommentMerchantRelationRequestConnector\CommentMerchantRelationRequestConnectorConfig::COMMENT_THREAD_MERCHANT_RELATION_REQUEST_OWNER_TYPE
     *
     * @var string
     */
    protected const COMMENT_THREAD_MERCHANT_RELATION_REQUEST_OWNER_TYPE = 'merchant_relation_request';

    /**
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    public function createMerchantRelationRequest(): MerchantRelationRequestTransfer
    {
        $merchantTransfer = $this->haveMerchant();
        $companyTransfer = $this->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $companyUserTransfer = $this->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $ownerCompanyBusinessUnit = $this->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $assigneeCompanyBusinessUnits = new ArrayObject([
            $this->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany()]),
            $this->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany()]),
        ]);

        $merchantRelationRequestTransfer = $this->haveMerchantRelationRequest([
            MerchantRelationRequestTransfer::STATUS => 'pending',
            MerchantRelationRequestTransfer::MERCHANT => $merchantTransfer,
            MerchantRelationRequestTransfer::COMPANY_USER => $companyUserTransfer,
            MerchantRelationRequestTransfer::OWNER_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnit,
            MerchantRelationRequestTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS => $assigneeCompanyBusinessUnits,
        ]);

        return $merchantRelationRequestTransfer
            ->setMerchant($merchantTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setOwnerCompanyBusinessUnit($ownerCompanyBusinessUnit);
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
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        return $this->haveMerchantRelationship([
            MerchantRelationshipTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantRelationshipTransfer::MERCHANT => $merchantTransfer,
            MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnit->getIdCompanyBusinessUnit(),
            MerchantRelationshipTransfer::OWNER_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnit,
            MerchantRelationshipTransfer::MERCHANT_RELATION_REQUEST_UUID => $merchantRelationRequestUuid,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    public function addCustomerCommentToMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): CommentThreadTransfer {
        $commentTransfer = (new CommentBuilder([
            CommentTransfer::CUSTOMER => $this->haveCustomer(),
        ]))->build();

        return $this->haveComment([
            CommentRequestTransfer::COMMENT => $commentTransfer->toArray(),
            CommentRequestTransfer::OWNER_TYPE => static::COMMENT_THREAD_MERCHANT_RELATION_REQUEST_OWNER_TYPE,
            CommentRequestTransfer::OWNER_ID => $merchantRelationRequestTransfer->getIdMerchantRelationRequest(),
        ])->getCommentThread();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    public function addUserCommentToMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): CommentThreadTransfer {
        $commentTransfer = (new CommentBuilder([
            CommentTransfer::FK_USER => $this->haveUser()->getIdUser(),
        ]))->build();

        return $this->haveComment([
            CommentRequestTransfer::COMMENT => $commentTransfer->toArray(),
            CommentRequestTransfer::OWNER_TYPE => static::COMMENT_THREAD_MERCHANT_RELATION_REQUEST_OWNER_TYPE,
            CommentRequestTransfer::OWNER_ID => $merchantRelationRequestTransfer->getIdMerchantRelationRequest(),
        ])->getCommentThread();
    }
}
