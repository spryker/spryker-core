<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRelationRequest\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\MerchantRelationRequestBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Client\MerchantRelationRequest\Plugin\Permission\CreateMerchantRelationRequestPermissionPlugin;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\CompanyRole\Communication\Plugin\PermissionStoragePlugin;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestDependencyProvider;
use Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostCreatePluginInterface;
use Spryker\Zed\Permission\PermissionDependencyProvider;
use SprykerTest\Zed\MerchantRelationRequest\MerchantRelationRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationRequest
 * @group Business
 * @group Facade
 * @group CreateMerchantRelationRequestCollectionTest
 * Add your own group annotations below this line
 */
class CreateMerchantRelationRequestCollectionTest extends Unit
{
    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_PENDING
     *
     * @var string
     */
    protected const STATUS_PENDING = 'pending';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\CompanyAccountCompatibilityValidatorRule::GLOSSARY_KEY_INCOMPATIBLE_COMPANY_ACCOUNT
     *
     * @var string
     */
    protected const GLOSSARY_KEY_INCOMPATIBLE_COMPANY_ACCOUNT = 'merchant_relation_request.validation.incompatible_company_account';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\EmptyDecisionNoteValidatorRule::GLOSSARY_KEY_DECISION_NOT_EMPTY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_DECISION_NOT_EMPTY = 'merchant_relation_request.validation.decision_note_empty';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\NotEmptyAssigneeBusinessUnitsInRequestValidatorRule::GLOSSARY_KEY_ASSIGNEE_BUSINESS_UNITS_EMPTY
     *
     * @var string
     */
    protected const GLOSSARY_KEY_ASSIGNEE_BUSINESS_UNITS_EMPTY = 'merchant_relation_request.validation.assignee_business_units_empty';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\PendingRequestStatusValidatorRule::GLOSSARY_KEY_STATUS_NOT_PENDING
     *
     * @var string
     */
    protected const GLOSSARY_KEY_STATUS_NOT_PENDING = 'merchant_relation_request.validation.status_not_pending';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\RequestNoteLengthValidatorRule::GLOSSARY_KEY_REQUEST_NOTE_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_REQUEST_NOTE_WRONG_LENGTH = 'merchant_relation_request.validation.request_note_wrong_length';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\UniqueAssigneeBusinessUnitsInRequestValidatorRule::GLOSSARY_KEY_ASSIGNEE_BUSINESS_UNITS_DUPLICATED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_ASSIGNEE_BUSINESS_UNITS_DUPLICATED = 'merchant_relation_request.validation.assignee_business_units_duplicated';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\CreateMerchantRelationRequestPermissionValidatorRule::PERMISSION_KEY_CREATE_MERCHANT_RELATION_REQUEST
     *
     * @var string
     */
    protected const GLOSSARY_KEY_COMPANY_USER_ACCESS_DENIED = 'merchant_relation_request.validation.company_user_access_denied';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\ActiveMerchantWithApprovedAccessValidatorRule::GLOSSARY_KEY_MERCHANT_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_MERCHANT_NOT_FOUND = 'merchant_relation_request.validation.merchant_not_found';

    /**
     * @var \SprykerTest\Zed\MerchantRelationRequest\MerchantRelationRequestBusinessTester
     */
    protected MerchantRelationRequestBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tester->ensureMerchantRelationRequestTablesAreEmpty();

        $this->tester->preparePermissionStorageDependency(new PermissionStoragePlugin());
        $this->tester->setDependency(PermissionDependencyProvider::PLUGINS_PERMISSION, [
            new CreateMerchantRelationRequestPermissionPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testShouldCreateNewMerchantRelationRequestInPersistence(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertEmpty($merchantRelationRequestCollectionResponseTransfer->getErrors());

        $this->assertSame(1, $this->tester->getMerchantRelationRequestQuery()->count());
        $this->assertSame(2, $this->tester->getMerchantRelationRequestToCompanyBusinessUnitQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldReturnRequestAfterCreation(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCreatedMerchantRelationRequest(
            $merchantRelationRequestTransfer,
            $merchantRelationRequestCollectionResponseTransfer,
        );
    }

    /**
     * @dataProvider mandatoryFieldsDataProvider
     *
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer
     * @param string $exceptionMessage
     *
     * @return void
     */
    public function testShouldRequireMandatoryFields(
        MerchantRelationRequestCollectionRequestTransfer $merchantRelationRequestCollectionRequestTransfer,
        string $exceptionMessage
    ): void {
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $this->expectExceptionMessage($exceptionMessage);

        // Act
        $this->tester->getFacade()->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldValidateCompanyAccountCompatibilityValidationRuleForCompanyUser(): void
    {
        // Arrange
        $newCompany = $this->tester->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $newCompanyUser = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->tester->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $newCompany->getIdCompany(),
        ]);

        $this->tester->assignCreateMerchantRelationRequestPermission($newCompanyUser);

        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestTransfer->setCompanyUser($newCompanyUser);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_INCOMPATIBLE_COMPANY_ACCOUNT,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateCompanyAccountCompatibilityValidationRuleForOwnerBusinessUnit(): void
    {
        // Arrange
        $newCompany = $this->tester->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $newOwnerCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $newCompany->getIdCompany(),
        ]);

        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestTransfer->setOwnerCompanyBusinessUnit($newOwnerCompanyBusinessUnit);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_INCOMPATIBLE_COMPANY_ACCOUNT,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateCompanyAccountCompatibilityValidationRuleForAssigneeBusinessUnit(): void
    {
        // Arrange
        $newCompany = $this->tester->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $newCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $newCompany->getIdCompany(),
        ]);

        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestTransfer->addAssigneeCompanyBusinessUnit($newCompanyBusinessUnit);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_INCOMPATIBLE_COMPANY_ACCOUNT,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateEmptyDecisionNoteValidatorRule(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestTransfer->setDecisionNote('Fake decision note');

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_DECISION_NOT_EMPTY,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateNotEmptyAssigneeBusinessUnitsInRequestValidatorRule(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestTransfer->setAssigneeCompanyBusinessUnits(new ArrayObject([]));

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_ASSIGNEE_BUSINESS_UNITS_EMPTY,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidatePendingRequestStatusValidatorRule(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestTransfer->setStatus('approved');

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_STATUS_NOT_PENDING,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateRequestNoteLengthValidatorRule(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestTransfer->setRequestNote($this->tester->generateRandomString(5001));

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_REQUEST_NOTE_WRONG_LENGTH,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldSkipRequestNoteLengthValidatorRule(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestTransfer->setRequestNote(null);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(0, $merchantRelationRequestCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldValidateUniqueAssigneeBusinessUnitsInRequestValidatorRule(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestTransfer->addAssigneeCompanyBusinessUnit(
            $merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits()->offsetGet(0),
        );

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_ASSIGNEE_BUSINESS_UNITS_DUPLICATED,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateCreateMerchantRelationRequestPermissionValidatorRule(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest(false);
        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_COMPANY_USER_ACCESS_DENIED,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateInActiveMerchantWithApprovedAccessValidatorRule(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestTransfer->setMerchant(
            $this->tester->haveMerchant([
                MerchantTransfer::IS_ACTIVE => false,
                MerchantTransfer::STATUS => 'approved',
            ]),
        );
        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_MERCHANT_NOT_FOUND,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateActiveMerchantWithoutApprovedAccessValidatorRule(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestTransfer->setMerchant(
            $this->tester->haveMerchant([
                MerchantTransfer::IS_ACTIVE => true,
                MerchantTransfer::STATUS => 'waiting-for-approval',
            ]),
        );
        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_MERCHANT_NOT_FOUND,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldCollectAllValidationMessagesIntoOneResponse(): void
    {
        // Arrange
        $newCompany = $this->tester->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $newCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $newCompany->getIdCompany(),
        ]);

        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestTransfer
            ->setStatus('dummy-status')
            ->setDecisionNote('dummy-decision-note')
            ->setRequestNote($this->tester->generateRandomString(5001))
            ->setOwnerCompanyBusinessUnit($newCompanyBusinessUnit)
            ->setAssigneeCompanyBusinessUnits((new ArrayObject([])));

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(5, $merchantRelationRequestCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldPersistValidEntitiesWhenIsTransactionalFlagDisabled(): void
    {
        // Arrange
        $merchantRelationRequest1 = $this->prepareMerchantRelationRequest();
        $merchantRelationRequest1->setStatus('rejected');
        $merchantRelationRequest2 = $this->prepareMerchantRelationRequest();

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([
                $merchantRelationRequest1,
                $merchantRelationRequest2,
            ]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->getMerchantRelationRequestQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldAvoidPersistingOfValidEntitiesWhenIsTransactionalFlagEnabled(): void
    {
        // Arrange
        $merchantRelationRequest1 = $this->prepareMerchantRelationRequest();
        $merchantRelationRequest1->setStatus('rejected');
        $merchantRelationRequest2 = $this->prepareMerchantRelationRequest();

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([
                $merchantRelationRequest1,
                $merchantRelationRequest2,
            ]))
            ->setIsTransactional(true);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(0, $this->tester->getMerchantRelationRequestQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldAvoidPersistingOfNotValidEntities(): void
    {
        // Arrange
        $merchantRelationRequest1 = $this->prepareMerchantRelationRequest();
        $merchantRelationRequest1->setStatus('rejected');

        $merchantRelationRequest2 = $this->prepareMerchantRelationRequest();
        $merchantRelationRequest2->setStatus('approved');

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([
                $merchantRelationRequest1,
                $merchantRelationRequest2,
            ]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(2, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(0, $this->tester->getMerchantRelationRequestQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldExecuteMerchantRelationRequestPostCreatePluginStack(): void
    {
        // Assert
        $merchantRelationRequestPostCreatePluginMock = $this->createMerchantRelationRequestPostCreatePluginMock();

        // Arrange
        $this->tester->setDependency(
            MerchantRelationRequestDependencyProvider::PLUGINS_MERCHANT_RELATION_REQUEST_POST_CREATE,
            [$merchantRelationRequestPostCreatePluginMock],
        );

        $merchantRelationRequestTransfer = $this->prepareMerchantRelationRequest();
        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $this->tester->getFacade()->createMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);
    }

    /**
     * @return array<array<string>>
     */
    public function mandatoryFieldsDataProvider(): array
    {
        return [
            'Requires `isTransactional` to be set.' => [
                (new MerchantRelationRequestCollectionRequestTransfer())
                    ->setMerchantRelationRequests(new ArrayObject()),
                'Missing required property "isTransactional" for transfer Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer.',
            ],
            'Requires `merchantRelationRequests` to be set.' => [
                (new MerchantRelationRequestCollectionRequestTransfer())
                    ->setIsTransactional(false),
                'Empty required collection property "merchantRelationRequests" for transfer Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer.',
            ],
            'Requires `status` to be set.' => [
                (new MerchantRelationRequestCollectionRequestTransfer())
                    ->setIsTransactional(false)
                    ->setMerchantRelationRequests(new ArrayObject([
                        $this->createDummyMerchantRelationRequest([
                            MerchantRelationRequestTransfer::STATUS => null,
                            MerchantTransfer::ID_MERCHANT => 1,
                            CompanyUserTransfer::ID_COMPANY_USER => 1,
                            CompanyBusinessUnitTransfer::FK_COMPANY => 1,
                            CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => 1,
                        ]),
                    ])),
                'Missing required property "status" for transfer Generated\Shared\Transfer\MerchantRelationRequestTransfer.',
            ],
            'Requires `companyUser.idCompanyUser` to be set.' => [
                (new MerchantRelationRequestCollectionRequestTransfer())
                    ->setIsTransactional(false)
                    ->setMerchantRelationRequests(new ArrayObject([
                        $this->createDummyMerchantRelationRequest([
                            MerchantRelationRequestTransfer::STATUS => 'pending',
                            MerchantTransfer::ID_MERCHANT => 1,
                            CompanyUserTransfer::ID_COMPANY_USER => null,
                            CompanyBusinessUnitTransfer::FK_COMPANY => 1,
                            CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => 1,
                        ]),
                    ])),
                'Missing required property "idCompanyUser" for transfer Generated\Shared\Transfer\CompanyUserTransfer.',
            ],
            'Requires `merchant.idMerchant` to be set.' => [
                (new MerchantRelationRequestCollectionRequestTransfer())
                    ->setIsTransactional(false)
                    ->setMerchantRelationRequests(new ArrayObject([
                        $this->createDummyMerchantRelationRequest([
                            MerchantRelationRequestTransfer::STATUS => 'pending',
                            MerchantTransfer::ID_MERCHANT => null,
                            CompanyUserTransfer::ID_COMPANY_USER => 1,
                            CompanyBusinessUnitTransfer::FK_COMPANY => 1,
                            CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => 1,
                        ]),
                    ])),
                'Missing required property "idMerchant" for transfer Generated\Shared\Transfer\MerchantTransfer.',
            ],
            'Requires `ownerCompanyBusinessUnit.idCompanyBusinessUnit` to be set.' => [
                (new MerchantRelationRequestCollectionRequestTransfer())
                    ->setIsTransactional(false)
                    ->setMerchantRelationRequests(new ArrayObject([
                        $this->createDummyMerchantRelationRequest([
                            MerchantRelationRequestTransfer::STATUS => 'pending',
                            MerchantTransfer::ID_MERCHANT => 1,
                            CompanyUserTransfer::ID_COMPANY_USER => 1,
                            CompanyBusinessUnitTransfer::FK_COMPANY => 1,
                            CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => null,
                        ]),
                    ])),
                'Missing required property "idCompanyBusinessUnit" for transfer Generated\Shared\Transfer\CompanyBusinessUnitTransfer.',
            ],
            'Requires `ownerCompanyBusinessUnit.fkCompany` to be set.' => [
                (new MerchantRelationRequestCollectionRequestTransfer())
                    ->setIsTransactional(false)
                    ->setMerchantRelationRequests(new ArrayObject([
                        $this->createDummyMerchantRelationRequest([
                            MerchantRelationRequestTransfer::STATUS => 'pending',
                            MerchantTransfer::ID_MERCHANT => 1,
                            CompanyUserTransfer::ID_COMPANY_USER => 1,
                            CompanyBusinessUnitTransfer::FK_COMPANY => null,
                            CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => 1,
                        ]),
                    ])),
                'Missing required property "fkCompany" for transfer Generated\Shared\Transfer\CompanyBusinessUnitTransfer.',
            ],
            'Requires at least one `assigneeCompanyBusinessUnits` to be set.' => [
                (new MerchantRelationRequestCollectionRequestTransfer())
                    ->setIsTransactional(false)
                    ->setMerchantRelationRequests(new ArrayObject([
                        $this->createDummyMerchantRelationRequest([
                            MerchantRelationRequestTransfer::STATUS => 'pending',
                            MerchantTransfer::ID_MERCHANT => 1,
                            CompanyUserTransfer::ID_COMPANY_USER => 1,
                            CompanyBusinessUnitTransfer::FK_COMPANY => 1,
                            CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT => 1,
                        ])->setAssigneeCompanyBusinessUnits(new ArrayObject([
                            new CompanyBusinessUnitTransfer(),
                        ])),
                    ])),
                'Missing required property "idCompanyBusinessUnit" for transfer Generated\Shared\Transfer\CompanyBusinessUnitTransfer.',
            ],
        ];
    }

    /**
     * @param array<string, string|int> $seed
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    protected function createDummyMerchantRelationRequest(array $seed): MerchantRelationRequestTransfer
    {
        return (new MerchantRelationRequestBuilder($seed))
            ->withMerchant()
            ->withCompanyUser()
            ->withOwnerCompanyBusinessUnit()
            ->withAssigneeCompanyBusinessUnit()
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return void
     */
    protected function assertCreatedMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): void {
        /** @var \Generated\Shared\Transfer\MerchantRelationRequestTransfer $persistedRequest */
        $persistedRequest = $merchantRelationRequestCollectionResponseTransfer->getMerchantRelationRequests()->offsetGet(0);

        $this->assertNotNull($persistedRequest->getIdMerchantRelationRequest());
        $this->assertNotNull($persistedRequest->getUuid());
        $this->assertNotNull($persistedRequest->getCreatedAt());
        $this->assertNull($persistedRequest->getDecisionNote());
        $this->assertFalse($persistedRequest->getIsSplitEnabled());
        $this->assertSame($merchantRelationRequestTransfer->getRequestNote(), $persistedRequest->getRequestNote());
        $this->assertSame($merchantRelationRequestTransfer->getStatus(), $persistedRequest->getStatus());
        $this->assertSame(
            $merchantRelationRequestTransfer->getMerchant()->getIdMerchant(),
            $persistedRequest->getMerchant()->getIdMerchant(),
        );
        $this->assertSame(
            $merchantRelationRequestTransfer->getCompanyUser()->getIdCompanyUser(),
            $persistedRequest->getCompanyUser()->getIdCompanyUser(),
        );
        $this->assertSame(
            $merchantRelationRequestTransfer->getOwnerCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
            $persistedRequest->getOwnerCompanyBusinessUnit()->getIdCompanyBusinessUnit(),
        );
        $this->assertSame(2, $persistedRequest->getAssigneeCompanyBusinessUnits()->count());
    }

    /**
     * @param bool|null $withCreatePermission
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    protected function prepareMerchantRelationRequest(?bool $withCreatePermission = true): MerchantRelationRequestTransfer
    {
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
            MerchantTransfer::STATUS => 'approved',
        ]);
        $companyTransfer = $this->tester->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $this->tester->haveCustomer(),
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        if ($withCreatePermission) {
            $this->tester->assignCreateMerchantRelationRequestPermission($companyUserTransfer);
        }

        $ownerCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);

        $assigneeCompanyBusinessUnits = new ArrayObject([
            $this->tester->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany()]),
            $this->tester->haveCompanyBusinessUnit([CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany()]),
        ]);

        return (new MerchantRelationRequestBuilder())->build()
            ->setStatus(static::STATUS_PENDING)
            ->setMerchant($merchantTransfer)
            ->setCompanyUser($companyUserTransfer)
            ->setOwnerCompanyBusinessUnit($ownerCompanyBusinessUnit)
            ->setAssigneeCompanyBusinessUnits($assigneeCompanyBusinessUnits);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostCreatePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMerchantRelationRequestPostCreatePluginMock(): MerchantRelationRequestPostCreatePluginInterface
    {
        $merchantRelationRequestPostCreatePluginMock = $this
            ->getMockBuilder(MerchantRelationRequestPostCreatePluginInterface::class)
            ->getMock();

        $merchantRelationRequestPostCreatePluginMock
            ->expects($this->once())
            ->method('postCreate');

        return $merchantRelationRequestPostCreatePluginMock;
    }
}
