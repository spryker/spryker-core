<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CommentMerchantRelationshipConnector;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
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
 * @method \Spryker\Zed\CommentMerchantRelationshipConnector\Business\CommentMerchantRelationshipConnectorFacadeInterface getFacade(?string $moduleName = NULL)
 *
 * @SuppressWarnings(\SprykerTest\Zed\CommentMerchantRelationshipConnector\PHPMD)
 */
class CommentMerchantRelationshipConnectorBusinessTester extends Actor
{
    use _generated\CommentMerchantRelationshipConnectorBusinessTesterActions;

    /**
     * @uses \Spryker\Zed\CommentMerchantRelationshipConnector\CommentMerchantRelationshipConnectorConfig::COMMENT_THREAD_MERCHANT_RELATIONSHIP_OWNER_TYPE
     *
     * @var string
     */
    protected const COMMENT_THREAD_MERCHANT_RELATIONSHIP_OWNER_TYPE = 'merchant_relationship';

    /**
     * @return \Generated\Shared\Transfer\MerchantRelationshipTransfer
     */
    public function createMerchantRelationship(): MerchantRelationshipTransfer
    {
        $merchantTransfer = $this->haveMerchant();
        $companyTransfer = $this->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $ownerCompanyBusinessUnit = $this->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $assigneeCompanyBusinessUnits = new ArrayObject([
            $this->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany()]),
            $this->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany()]),
        ]);

        return $this->haveMerchantRelationship([
            MerchantRelationshipTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchant(),
            MerchantRelationshipTransfer::MERCHANT => $merchantTransfer,
            MerchantRelationshipTransfer::FK_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnit->getIdCompanyBusinessUnit(),
            MerchantRelationshipTransfer::OWNER_COMPANY_BUSINESS_UNIT => $ownerCompanyBusinessUnit,
            MerchantRelationshipTransfer::ASSIGNEE_COMPANY_BUSINESS_UNITS =>
                (new CompanyBusinessUnitCollectionTransfer())->setCompanyBusinessUnits($assigneeCompanyBusinessUnits),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    public function addCustomerCommentToMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): CommentThreadTransfer {
        $commentTransfer = (new CommentBuilder([
            CommentTransfer::CUSTOMER => $this->haveCustomer(),
        ]))->build();

        return $this->haveComment([
            CommentRequestTransfer::COMMENT => $commentTransfer->toArray(),
            CommentRequestTransfer::OWNER_TYPE => static::COMMENT_THREAD_MERCHANT_RELATIONSHIP_OWNER_TYPE,
            CommentRequestTransfer::OWNER_ID => $merchantRelationshipTransfer->getIdMerchantRelationship(),
        ])->getCommentThread();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer
     */
    public function addUserCommentToMerchantRelationship(
        MerchantRelationshipTransfer $merchantRelationshipTransfer
    ): CommentThreadTransfer {
        $commentTransfer = (new CommentBuilder([
            CommentTransfer::FK_USER => $this->haveUser()->getIdUser(),
        ]))->build();

        return $this->haveComment([
            CommentRequestTransfer::COMMENT => $commentTransfer->toArray(),
            CommentRequestTransfer::OWNER_TYPE => static::COMMENT_THREAD_MERCHANT_RELATIONSHIP_OWNER_TYPE,
            CommentRequestTransfer::OWNER_ID => $merchantRelationshipTransfer->getIdMerchantRelationship(),
        ])->getCommentThread();
    }
}
