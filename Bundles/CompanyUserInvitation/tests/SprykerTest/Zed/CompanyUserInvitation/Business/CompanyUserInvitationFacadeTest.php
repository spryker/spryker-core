<?php

namespace SprykerTest\Zed\CompanyUserInvitation\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CompanyUserInvitationBuilder;
use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUserInvitation
 * @group Business
 * @group Facade
 * @group CompanyUserInvitationFacadeTest
 * Add your own group annotations below this line
 */
class CompanyUserInvitationFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\CompanyUserInvitation\CompanyUserInvitationBusinessTester
     */
    protected $tester;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\CompanyTransfer
     */
    protected $companyTransfer;

    /**
     * @var \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    protected $companyBusinessUnitTransfer;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransfer;

    /**
     * @return void
     */
    public function testImportCompanyUserInvitationsShouldReturnNoErrorsWhenEmailsDoNotExist()
    {
        $this->haveRequiredData();
        $companyUserInvitationCollection = new CompanyUserInvitationCollectionTransfer();
        $companyUserInvitationCollection->addInvitation($this->generateCompanyUserInvitationTransfer());
        $companyUserInvitationCollection->addInvitation($this->generateCompanyUserInvitationTransfer());

        $companyUserInvitationImportResultTransfer = $this->getFacade()
            ->importCompanyUserInvitations($companyUserInvitationCollection);

        $this->assertEmpty($companyUserInvitationImportResultTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testImportCompanyUserInvitationsShouldReturnErrorsWhenEmailExist()
    {
        $this->haveRequiredData();
        $companyUserInvitationCollection = new CompanyUserInvitationCollectionTransfer();
        $companyUserInvitationTransfer = $this->generateCompanyUserInvitationTransfer();
        $companyUserInvitationCollection->addInvitation($companyUserInvitationTransfer);
        $this->haveCompanyUserInvitation(['email' => $companyUserInvitationTransfer->getEmail()]);

        $companyUserInvitationImportResultTransfer = $this->getFacade()
            ->importCompanyUserInvitations($companyUserInvitationCollection);

        $this->assertNotEmpty($companyUserInvitationImportResultTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testSendCompanyUserInvitationShouldReturnSuccess()
    {
        $this->haveRequiredData();
        $companyUserInvitationTransfer = $this->haveCompanyUserInvitation();

        $companyUserInvitationSendResultTransfer = $this->getFacade()
            ->sendCompanyUserInvitation($companyUserInvitationTransfer);

        $this->assertTrue($companyUserInvitationSendResultTransfer->getSuccess());
    }

    /**
     * @return void
     */
    public function testSendCompanyUserInvitationsShouldReturnSuccess()
    {
        $this->haveRequiredData();
        $this->haveCompanyUserInvitation();
        $this->haveCompanyUserInvitation();

        $companyUserInvitationSendBatchResultTransfer = $this->getFacade()
            ->sendCompanyUserInvitations($this->companyUserTransfer);

        $invitationsSent = $companyUserInvitationSendBatchResultTransfer->getInvitationsTotal()
            - $companyUserInvitationSendBatchResultTransfer->getInvitationsFailed();
        $this->assertEquals(2, $invitationsSent);
        $this->assertEmpty(0, $companyUserInvitationSendBatchResultTransfer->getInvitationsFailed());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserInvitationByHashShouldReturnCorrectData()
    {
        $this->haveRequiredData();
        $companyUserInvitationTransfer = $this->haveCompanyUserInvitation();

        $queryResultCompanyUserInvitationTransfer = $this->getFacade()->getCompanyUserInvitationByHash($companyUserInvitationTransfer);

        $this->assertEquals(
            $companyUserInvitationTransfer->getIdCompanyUserInvitation(),
            $queryResultCompanyUserInvitationTransfer->getIdCompanyUserInvitation()
        );
    }

    /**
     * @return void
     */
    public function testUpdateCompanyUserInvitationStatusShouldReturnSuccess()
    {
        $this->haveRequiredData();
        $companyUserInvitationTransfer = $this->haveCompanyUserInvitation();
        $companyUserInvitationUpdateStatusRequestTransfer = new CompanyUserInvitationUpdateStatusRequestTransfer();
        $companyUserInvitationUpdateStatusRequestTransfer->setStatusKey(CompanyUserInvitationConstants::INVITATION_STATUS_ACCEPTED);
        $companyUserInvitationUpdateStatusRequestTransfer->setCompanyUserInvitation($companyUserInvitationTransfer);

        $companyUserInvitationUpdateStatusResultTransfer = $this->getFacade()
            ->updateCompanyUserInvitationStatus($companyUserInvitationUpdateStatusRequestTransfer);

        $this->assertTrue($companyUserInvitationUpdateStatusResultTransfer->getSuccess());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserInvitationCollectionShouldReturnCorrectData()
    {
        $this->haveRequiredData();
        $this->haveCompanyUserInvitation();
        $this->haveCompanyUserInvitation();
        $criteriaFilterTransfer = (new CompanyUserInvitationCriteriaFilterTransfer())
            ->setFkCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCompanyUserInvitationStatusKeyIn([CompanyUserInvitationConstants::INVITATION_STATUS_NEW]);

        $companyUserInvitationCollectionTransfer = $this->getFacade()
            ->getCompanyUserInvitationCollection($criteriaFilterTransfer);

        $this->assertEquals(2, $companyUserInvitationCollectionTransfer->getInvitations()->count());
    }

    /**
     * @return void
     */
    protected function haveRequiredData()
    {
        $this->customerTransfer = $this->tester->haveCustomer();
        $this->companyTransfer = $this->tester->haveCompany(['is_active' => true]);
        $this->companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            'fk_company' => $this->companyTransfer->getIdCompany(),
        ]);
        $this->companyUserTransfer = $this->tester->haveCompanyUser([
            'fk_customer' => $this->customerTransfer->getIdCustomer(),
            'fk_company' => $this->companyTransfer->getIdCompany(),
            'fk_company_business_unit' => $this->companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
        ]);
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    protected function generateCompanyUserInvitationTransfer(array $seedData = []): CompanyUserInvitationTransfer
    {
        $seedData = $seedData + [
            'fk_company_user' => $this->companyUserTransfer->getIdCompanyUser(),
            'company_business_unit_name' => $this->companyBusinessUnitTransfer->getName(),
        ];

        return (new CompanyUserInvitationBuilder($seedData))->build();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    protected function haveCompanyUserInvitation(array $seedData = []): CompanyUserInvitationTransfer
    {
        $seedData = $seedData + [
            'fk_company_user' => $this->companyUserTransfer->getIdCompanyUser(),
            'company_business_unit_name' => $this->companyBusinessUnitTransfer->getName(),
        ];

        return $this->tester->haveCompanyUserInvitation($seedData);
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
