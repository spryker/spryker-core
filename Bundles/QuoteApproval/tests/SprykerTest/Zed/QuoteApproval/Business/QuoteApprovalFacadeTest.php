<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteApproval\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteApprovalRequestTransfer;
use Orm\Zed\Quote\Persistence\SpyQuote;
use Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval;
use Orm\Zed\QuoteApproval\Persistence\SpyQuoteApprovalQuery;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ApproveQuotePermissionPlugin;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\Permission\PermissionDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group QuoteApproval
 * @group Business
 * @group Facade
 * @group QuoteApprovalFacadeTest
 * Add your own group annotations below this line
 */
class QuoteApprovalFacadeTest extends Unit
{
    protected const COMPANY_KEY = 'COMPANY_KEY';
    protected const COMPANY_USER_KEY = 'COMPANY_USER_KEY';
    protected const CART_NAME = 'CART_NAME';
    protected const CART_KEY = 'CART_KEY';
    protected const CART_DATA = 'CART_DATA';

    /**
     * @var \SprykerTest\Zed\QuoteApproval\QuoteApprovalBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransfer;

    /**
     * @var \Generated\Shared\Transfer\CompanyRoleTransfer
     */
    protected $companyRole;

    /**
     * @var int
     */
    protected $idQuote;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [
            new ApproveQuotePermissionPlugin(),
        ]);

        $this->tester->getLocator()->permission()->facade()->syncPermissionPlugins();

        $customerTransfer = $this->tester->haveCustomer();

        $companyTransfer = $this->tester->haveCompany([
            CompanyTransfer::KEY => static::COMPANY_KEY,
        ]);

        $this->companyRole = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $this->companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::KEY => static::COMPANY_USER_KEY,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::COMPANY_ROLE_COLLECTION => new ArrayObject($this->companyRole),
        ]);

        $storeTransfer = $this->tester->haveStore();

        $quoteEntity = new SpyQuote();
        $quoteEntity
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setName(static::CART_NAME)
            ->setKey(static::CART_KEY)
            ->setQuoteData(json_encode([static::CART_DATA]))
            ->setFkStore($storeTransfer->getIdStore())
            ->save();

        $this->idQuote = $quoteEntity->getIdQuote();
    }

    /**
     * @return void
     */
    public function testApproveQuoteWithEmptyPermissionSuccess(): void
    {
        $quoteApprovalEntity = $this->createQuoteApprovalEntity();

        $quoteApprovalRequestTransfer = (new QuoteApprovalRequestTransfer())
            ->setFkCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setIdQuoteApproval($quoteApprovalEntity->getIdQuoteApproval());

        /** @var \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer */
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->approveQuote($quoteApprovalRequestTransfer);

        $this->assertTrue($quoteApprovalResponseTransfer->getIsSuccessful());
        $this->assertSame($quoteApprovalResponseTransfer->getQuoteApproval()->getStatus(), QuoteApprovalConfig::STATUS_APPROVED);
    }

    /**
     * @return void
     */
    public function testDeclineQuoteWithEmptyPermissionSuccess(): void
    {
        $quoteApprovalEntity = $this->createQuoteApprovalEntity();

        $quoteApprovalRequestTransfer = (new QuoteApprovalRequestTransfer())
            ->setFkCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setIdQuoteApproval($quoteApprovalEntity->getIdQuoteApproval());

        /** @var \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer */
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->declineQuote($quoteApprovalRequestTransfer);

        $this->assertTrue($quoteApprovalResponseTransfer->getIsSuccessful());
        $this->assertSame($quoteApprovalResponseTransfer->getQuoteApproval()->getStatus(), QuoteApprovalConfig::STATUS_DECLINED);
    }

    /**
     * @return void
     */
    public function testCancelQuoteWithEmptyPermissionSuccess(): void
    {
        $quoteApprovalEntity = $this->createQuoteApprovalEntity();
        $quoteApprovalRequestTransfer = (new QuoteApprovalRequestTransfer())
            ->setFkCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setIdQuoteApproval($quoteApprovalEntity->getIdQuoteApproval());

        /** @var \Generated\Shared\Transfer\QuoteApprovalResponseTransfer $quoteApprovalResponseTransfer */
        $quoteApprovalResponseTransfer = $this->tester->getFacade()->cancelQuote($quoteApprovalRequestTransfer);

        $quoteApprovalCount = (new SpyQuoteApprovalQuery())
            ->findByIdQuoteApproval($quoteApprovalEntity->getIdQuoteApproval())
            ->count();

        $this->assertTrue($quoteApprovalResponseTransfer->getIsSuccessful());
        $this->assertEmpty($quoteApprovalCount);
    }

    /**
     * @return \Orm\Zed\QuoteApproval\Persistence\SpyQuoteApproval
     */
    protected function createQuoteApprovalEntity(): SpyQuoteApproval
    {
        $quoteApprovalEntity = new SpyQuoteApproval();
        $quoteApprovalEntity->setFkCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setStatus(QuoteApprovalConfig::STATUS_WAITING)
            ->setFkQuote($this->idQuote)
            ->save();

        return $quoteApprovalEntity;
    }
}
