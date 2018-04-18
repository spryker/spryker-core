<?php

namespace SprykerTest\Zed\CompanyUserInvitation\Business;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CompanyRoleCollectionBuilder;
use Generated\Shared\DataBuilder\CompanyUserInvitationBuilder;
use Generated\Shared\DataBuilder\PermissionCollectionBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCreateRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationDeleteRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationGetCollectionRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationImportRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationSendRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationUpdateStatusRequestTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Shared\CompanyUserInvitation\CompanyUserInvitationConstants;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\CompanyUserInvitation\Communication\Plugin\Permission\ManageCompanyUserInvitationPermissionPlugin;
use Spryker\Zed\Permission\PermissionDependencyProvider;

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
    public const PERMISSION_PLUGINS = [
        ManageCompanyUserInvitationPermissionPlugin::class,
    ];

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
     * @var \Generated\Shared\Transfer\PermissionCollectionTransfer
     */
    protected $permissionCollectionTransfer;

    /**
     * @return void
     */
    public function testImportCompanyUserInvitationsShouldReturnNoErrorsWhenUserHasPermissionsAndEmailsDoNotExist()
    {
        $this->haveRequiredDataWithCompanyUserRoles();
        $companyUserInvitationCollection = (new CompanyUserInvitationCollectionTransfer())
            ->addCompanyUserInvitation($this->generateCompanyUserInvitationTransfer())
            ->addCompanyUserInvitation($this->generateCompanyUserInvitationTransfer());
        $companyUserInvitationImportRequestTransfer = (new CompanyUserInvitationImportRequestTransfer())
            ->setCompanyUserInvitationCollection($companyUserInvitationCollection)
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser());

        $companyUserInvitationImportResponseTransfer = $this->getFacade()
            ->importCompanyUserInvitations($companyUserInvitationImportRequestTransfer);

        $this->assertTrue($companyUserInvitationImportResponseTransfer->getIsSuccess());
        $this->assertEmpty($companyUserInvitationImportResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testImportCompanyUserInvitationsShouldReturnErrorsWhenUserHasPermissionsAndEmailsExist()
    {
        $this->haveRequiredDataWithCompanyUserRoles();
        $companyUserInvitationTransfer = $this->generateCompanyUserInvitationTransfer();
        $this->haveCompanyUserInvitation(['email' => $companyUserInvitationTransfer->getEmail()]);
        $companyUserInvitationCollection = (new CompanyUserInvitationCollectionTransfer())
            ->addCompanyUserInvitation($companyUserInvitationTransfer);
        $companyUserInvitationImportRequestTransfer = (new CompanyUserInvitationImportRequestTransfer())
            ->setCompanyUserInvitationCollection($companyUserInvitationCollection)
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser());

        $companyUserInvitationImportResponseTransfer = $this->getFacade()
            ->importCompanyUserInvitations($companyUserInvitationImportRequestTransfer);

        $this->assertTrue($companyUserInvitationImportResponseTransfer->getIsSuccess());
        $this->assertNotEmpty($companyUserInvitationImportResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testImportCompanyUserInvitationsShouldFailWhenUserHasNoPermissions()
    {
        $this->haveRequiredDataWithoutCompanyUserRoles();
        $companyUserInvitationCollection = (new CompanyUserInvitationCollectionTransfer())
            ->addCompanyUserInvitation($this->generateCompanyUserInvitationTransfer())
            ->addCompanyUserInvitation($this->generateCompanyUserInvitationTransfer());
        $companyUserInvitationImportRequestTransfer = (new CompanyUserInvitationImportRequestTransfer())
            ->setCompanyUserInvitationCollection($companyUserInvitationCollection)
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser());

        $companyUserInvitationImportResponseTransfer = $this->getFacade()
            ->importCompanyUserInvitations($companyUserInvitationImportRequestTransfer);

        $this->assertFalse($companyUserInvitationImportResponseTransfer->getIsSuccess());
        $this->assertEmpty($companyUserInvitationImportResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserInvitationCollectionShouldReturnCorrectDataWhenUserHasPermission()
    {
        $this->haveRequiredDataWithCompanyUserRoles();
        $this->haveCompanyUserInvitation();
        $this->haveCompanyUserInvitation();
        $criteriaFilterTransfer = (new CompanyUserInvitationCriteriaFilterTransfer())
            ->setFkCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCompanyUserInvitationStatusKeyIn([CompanyUserInvitationConstants::INVITATION_STATUS_NEW]);
        $companyUserInvitationGetCollectionRequestTransfer = (new CompanyUserInvitationGetCollectionRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCriteriaFilter($criteriaFilterTransfer);

        $companyUserInvitationCollectionTransfer = $this->getFacade()
            ->getCompanyUserInvitationCollection($companyUserInvitationGetCollectionRequestTransfer);

        $this->assertEquals(2, $companyUserInvitationCollectionTransfer->getCompanyUserInvitations()->count());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserInvitationCollectionShouldReturnNoDataWhenUserHasNoPermission()
    {
        $this->haveRequiredDataWithoutCompanyUserRoles();
        $this->haveCompanyUserInvitation();
        $this->haveCompanyUserInvitation();
        $criteriaFilterTransfer = (new CompanyUserInvitationCriteriaFilterTransfer())
            ->setFkCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCompanyUserInvitationStatusKeyIn([CompanyUserInvitationConstants::INVITATION_STATUS_NEW]);
        $companyUserInvitationGetCollectionRequestTransfer = (new CompanyUserInvitationGetCollectionRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCriteriaFilter($criteriaFilterTransfer);

        $companyUserInvitationCollectionTransfer = $this->getFacade()
            ->getCompanyUserInvitationCollection($companyUserInvitationGetCollectionRequestTransfer);

        $this->assertEquals(0, $companyUserInvitationCollectionTransfer->getCompanyUserInvitations()->count());
    }

    /**
     * @return void
     */
    public function testSendCompanyUserInvitationShouldReturnSuccessWhenUserHasPermission()
    {
        $this->haveRequiredDataWithCompanyUserRoles();
        $companyUserInvitationSendRequestTransfer = (new CompanyUserInvitationSendRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCompanyUserInvitation($this->haveCompanyUserInvitation());

        $companyUserInvitationSendResponseTransfer = $this->getFacade()
            ->sendCompanyUserInvitation($companyUserInvitationSendRequestTransfer);

        $this->assertTrue($companyUserInvitationSendResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSendCompanyUserInvitationShouldFailWhenUserHasNoPermission()
    {
        $this->haveRequiredDataWithoutCompanyUserRoles();
        $companyUserInvitationSendRequestTransfer = (new CompanyUserInvitationSendRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCompanyUserInvitation($this->haveCompanyUserInvitation());

        $companyUserInvitationSendResponseTransfer = $this->getFacade()
            ->sendCompanyUserInvitation($companyUserInvitationSendRequestTransfer);

        $this->assertFalse($companyUserInvitationSendResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSendCompanyUserInvitationsShouldReturnSuccessWhenUserHasPermission()
    {
        $this->haveRequiredDataWithCompanyUserRoles();
        $this->haveCompanyUserInvitation();
        $this->haveCompanyUserInvitation();

        $companyUserInvitationSendBatchResponseTransfer = $this->getFacade()
            ->sendCompanyUserInvitations($this->companyUserTransfer);

        $this->assertTrue($companyUserInvitationSendBatchResponseTransfer->getIsSuccess());
        $this->assertEmpty(0, $companyUserInvitationSendBatchResponseTransfer->getInvitationsFailed());
    }

    /**
     * @return void
     */
    public function testSendCompanyUserInvitationsShouldFailWhenUserHasNoPermission()
    {
        $this->haveRequiredDataWithoutCompanyUserRoles();
        $this->haveCompanyUserInvitation();
        $this->haveCompanyUserInvitation();

        $companyUserInvitationSendBatchResponseTransfer = $this->getFacade()
            ->sendCompanyUserInvitations($this->companyUserTransfer);

        $this->assertFalse($companyUserInvitationSendBatchResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateCompanyUserInvitationStatusShouldReturnSuccessWhenUserHasPermission()
    {
        $this->haveRequiredDataWithCompanyUserRoles();
        $companyUserInvitationTransfer = $this->haveCompanyUserInvitation();
        $companyUserInvitationUpdateStatusRequestTransfer = (new CompanyUserInvitationUpdateStatusRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setStatusKey(CompanyUserInvitationConstants::INVITATION_STATUS_ACCEPTED)
            ->setCompanyUserInvitation($companyUserInvitationTransfer);

        $companyUserInvitationUpdateStatusResponseTransfer = $this->getFacade()
            ->updateCompanyUserInvitationStatus($companyUserInvitationUpdateStatusRequestTransfer);

        $this->assertTrue($companyUserInvitationUpdateStatusResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateCompanyUserInvitationStatusShouldFailWhenUserHasNoPermission()
    {
        $this->haveRequiredDataWithoutCompanyUserRoles();
        $companyUserInvitationTransfer = $this->haveCompanyUserInvitation();
        $companyUserInvitationUpdateStatusRequestTransfer = (new CompanyUserInvitationUpdateStatusRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setStatusKey(CompanyUserInvitationConstants::INVITATION_STATUS_ACCEPTED)
            ->setCompanyUserInvitation($companyUserInvitationTransfer);

        $companyUserInvitationUpdateStatusResponseTransfer = $this->getFacade()
            ->updateCompanyUserInvitationStatus($companyUserInvitationUpdateStatusRequestTransfer);

        $this->assertFalse($companyUserInvitationUpdateStatusResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testGetCompanyUserInvitationByHashShouldReturnCorrectData()
    {
        $this->haveRequiredDataWithoutCompanyUserRoles();
        $companyUserInvitationTransfer = $this->haveCompanyUserInvitation();

        $queryResultCompanyUserInvitationTransfer = $this->getFacade()
            ->getCompanyUserInvitationByHash($companyUserInvitationTransfer);

        $this->assertEquals(
            $companyUserInvitationTransfer->getIdCompanyUserInvitation(),
            $queryResultCompanyUserInvitationTransfer->getIdCompanyUserInvitation()
        );
    }

    /**
     * @return void
     */
    public function testCreateCompanyUserInvitationShouldReturnSuccessWhenUserHasPermission()
    {
        $this->haveRequiredDataWithCompanyUserRoles();
        $companyUserInvitationCreateRequestTransfer = (new CompanyUserInvitationCreateRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCompanyUserInvitation($this->generateCompanyUserInvitationTransfer());

        $companyUserInvitationCreateResponseTransfer = $this->getFacade()
            ->createCompanyUserInvitation($companyUserInvitationCreateRequestTransfer);

        $this->assertTrue($companyUserInvitationCreateResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCreateCompanyUserInvitationShouldFailWhenUserHasNoPermission()
    {
        $this->haveRequiredDataWithoutCompanyUserRoles();
        $companyUserInvitationCreateRequestTransfer = (new CompanyUserInvitationCreateRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCompanyUserInvitation($this->generateCompanyUserInvitationTransfer());

        $companyUserInvitationCreateResponseTransfer = $this->getFacade()
            ->createCompanyUserInvitation($companyUserInvitationCreateRequestTransfer);

        $this->assertFalse($companyUserInvitationCreateResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testDeleteCompanyUserInvitationShouldReturnSuccessWhenUserHasPermission()
    {
        $this->haveRequiredDataWithCompanyUserRoles();
        $companyUserInvitationDeleteRequestTransfer = (new CompanyUserInvitationDeleteRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCompanyUserInvitation($this->haveCompanyUserInvitation());

        $companyUserInvitationDeleteResponseTransfer = $this->getFacade()
            ->deleteCompanyUserInvitation($companyUserInvitationDeleteRequestTransfer);

        $this->assertTrue($companyUserInvitationDeleteResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testDeleteCompanyUserInvitationShouldFailWhenUserHasNoPermission()
    {
        $this->haveRequiredDataWithoutCompanyUserRoles();
        $companyUserInvitationDeleteRequestTransfer = (new CompanyUserInvitationDeleteRequestTransfer())
            ->setIdCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setCompanyUserInvitation($this->haveCompanyUserInvitation());

        $companyUserInvitationDeleteResponseTransfer = $this->getFacade()
            ->deleteCompanyUserInvitation($companyUserInvitationDeleteRequestTransfer);

        $this->assertFalse($companyUserInvitationDeleteResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    protected function haveRequiredDataWithoutCompanyUserRoles(): void
    {
        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION_STORAGE, [
            new PermissionStoragePlugin(),
        ]);

        $this->customerTransfer = $this->tester->haveCustomer();
        $this->companyTransfer = $this->tester->haveCompany();
        $this->companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->companyTransfer->getIdCompany(),
        ]);

        $this->companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => $this->companyBusinessUnitTransfer->getIdCompanyBusinessUnit(),
            CompanyUserTransfer::FK_COMPANY => $this->companyTransfer->getIdCompany(),
            CompanyUserTransfer::CUSTOMER => $this->customerTransfer,
        ]);

        $this->permissionCollectionTransfer = (new PermissionCollectionBuilder())->build();
        foreach (static::PERMISSION_PLUGINS as $permissionPlugin) {
            $permissionTransfer = $this->tester->havePermission(new $permissionPlugin);
            $this->permissionCollectionTransfer->addPermission($permissionTransfer);
        }
    }

    /**
     * @return void
     */
    protected function haveRequiredDataWithCompanyUserRoles(): void
    {
        $this->haveRequiredDataWithoutCompanyUserRoles();
        $this->setUpCompanyUserRoles();
    }

    /**
     * @return void
     */
    protected function setUpCompanyUserRoles(): void
    {
        $companyRoleTransfer = $this->tester->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $this->companyTransfer->getIdCompany(),
            CompanyRoleTransfer::PERMISSION_COLLECTION => $this->permissionCollectionTransfer,
        ]);
        $companyRoleCollectionTransfer = (new CompanyRoleCollectionBuilder())->build()
            ->addRole($companyRoleTransfer);

        $this->companyUserTransfer->setCompanyRoleCollection($companyRoleCollectionTransfer);
        $this->tester->assignCompanyRolesToCompanyUser($this->companyUserTransfer);
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    protected function generateCompanyUserInvitationTransfer(array $seedData = []): CompanyUserInvitationTransfer
    {
        $seedData = $seedData + [
                CompanyUserInvitationTransfer::FK_COMPANY_USER => $this->companyUserTransfer->getIdCompanyUser(),
                CompanyUserInvitationTransfer::COMPANY_BUSINESS_UNIT_NAME => $this->companyBusinessUnitTransfer->getName(),
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
            CompanyUserInvitationTransfer::FK_COMPANY_USER => $this->companyUserTransfer->getIdCompanyUser(),
            CompanyUserInvitationTransfer::COMPANY_BUSINESS_UNIT_NAME => $this->companyBusinessUnitTransfer->getName(),
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
