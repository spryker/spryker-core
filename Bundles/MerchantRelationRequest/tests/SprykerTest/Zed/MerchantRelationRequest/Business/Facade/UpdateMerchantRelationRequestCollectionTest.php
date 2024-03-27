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
use Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationshipResponseTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\MerchantRelationRequest\Business\Exception\MerchantRelationRequestUpdateStrategyNotFoundException;
use Spryker\Zed\MerchantRelationRequest\Business\Exception\MerchantRelationshipNotCreatedException;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestDependencyProvider;
use Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostUpdatePluginInterface;
use SprykerTest\Zed\MerchantRelationRequest\MerchantRelationRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRelationRequest
 * @group Business
 * @group Facade
 * @group UpdateMerchantRelationRequestCollectionTest
 * Add your own group annotations below this line
 */
class UpdateMerchantRelationRequestCollectionTest extends Unit
{
    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_PENDING
     *
     * @var string
     */
    protected const STATUS_PENDING = 'pending';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_CANCELED
     *
     * @var string
     */
    protected const STATUS_CANCELED = 'canceled';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_REJECTED
     *
     * @var string
     */
    protected const STATUS_REJECTED = 'rejected';

    /**
     * @uses \Spryker\Shared\MerchantRelationRequest\MerchantRelationRequestConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const STATUS_APPROVED = 'approved';

    /**
     * @var string
     */
    protected const FAKE_UUID = 'FAKE_UUID';

    /**
     * @var string
     */
    protected const FAKE_DECISION_NOTE = 'fake decision note';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\DecisionNoteLengthValidatorRule::GLOSSARY_KEY_DECISION_NOTE_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_DECISION_NOTE_WRONG_LENGTH = 'merchant_relation_request.validation.decision_note_wrong_length';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\IsApprovableRequestValidatorRule::GLOSSARY_KEY_REQUEST_CANT_BE_APPROVED
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\IsRejectableRequestValidatorRule::GLOSSARY_KEY_REQUEST_CANT_BE_APPROVED
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\IsCancelableRequestValidatorRule::GLOSSARY_KEY_REQUEST_CANT_BE_APPROVED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_REQUEST_NOT_FOUND = 'merchant_relation_request.validation.not_found';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\IsApprovableRequestValidatorRule::GLOSSARY_KEY_REQUEST_CANT_BE_APPROVED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_REQUEST_CANT_BE_APPROVED = 'merchant_relation_request.validation.cant_be_approved';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\IsRejectableRequestValidatorRule::GLOSSARY_KEY_REQUEST_CANT_BE_REJECTED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_REQUEST_CANT_BE_REJECTED = 'merchant_relation_request.validation.cant_be_rejected';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\IsCancelableRequestValidatorRule::GLOSSARY_KEY_REQUEST_CANT_BE_CANCELED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_REQUEST_CANT_BE_CANCELED = 'merchant_relation_request.validation.cant_be_canceled';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\IsAllowedToUpdateToPendingValidatorRule::GLOSSARY_KEY_REQUEST_CANT_BECOME_PENDING
     *
     * @var string
     */
    protected const GLOSSARY_KEY_REQUEST_CANT_BECOME_PENDING = 'merchant_relation_request.validation.cant_become_pending';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequest\Business\Validator\Rule\MerchantRelationRequest\CompanyAccountCompatibilityValidatorRule::GLOSSARY_KEY_INCOMPATIBLE_COMPANY_ACCOUNT
     *
     * @var string
     */
    protected const GLOSSARY_KEY_INCOMPATIBLE_COMPANY_ACCOUNT = 'merchant_relation_request.validation.incompatible_company_account';

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
        $this->tester->ensureMerchantRelationshipTablesAreEmpty();
    }

    /**
     * @return void
     */
    public function testShouldCancelMerchantRelationRequestInPersistence(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_CANCELED);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertEmpty($merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->getMerchantRelationRequestQuery()->filterByStatus(static::STATUS_CANCELED)->count());
    }

    /**
     * @return void
     */
    public function testShouldRejectMerchantRelationRequestInPersistence(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_REJECTED);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $this->tester->getFacade()->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getMerchantRelationRequestQuery()->filterByStatus(static::STATUS_REJECTED)->count());
    }

    /**
     * @return void
     */
    public function testShouldApproveMerchantRelationRequestInPersistence(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_APPROVED)
            ->addAssigneeCompanyBusinessUnit($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits()->offsetGet(0));

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $this->tester->getFacade()->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getMerchantRelationRequestQuery()->filterByStatus(static::STATUS_APPROVED)->count());
        $this->assertSame(1, $this->tester->getMerchantRelationRequestToCompanyBusinessUnitQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldUpdatePendingMerchantRelationRequestInPersistence(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequestTransfer
            ->setDecisionNote(static::FAKE_DECISION_NOTE)
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_PENDING)
            ->setAssigneeCompanyBusinessUnits(new ArrayObject([$merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits()->offsetGet(0)]));

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $this->tester->getFacade()->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getMerchantRelationRequestQuery()
            ->filterByStatus(static::STATUS_PENDING)
            ->filterByDecisionNote(static::FAKE_DECISION_NOTE)
            ->count());
        $this->assertSame(1, $this->tester->getMerchantRelationRequestToCompanyBusinessUnitQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldUpdatePendingMerchantRelationRequestWithNewAssigneeCompanyBusinessUnitInPersistence(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $merchantRelationRequestTransfer->getCompanyUserOrFail()->getCompanyOrFail()->getIdCompanyOrFail(),
        ]);
        $merchantRelationRequestTransfer
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_PENDING)
            ->addAssigneeCompanyBusinessUnit($companyBusinessUnitTransfer);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $this->tester->getFacade()->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getMerchantRelationRequestQuery()
            ->filterByStatus(static::STATUS_PENDING)
            ->count());
        $this->assertSame(3, $this->tester->getMerchantRelationRequestToCompanyBusinessUnitQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldCreateMerchantRelationshipsInPersistence(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_APPROVED)
            ->setAssigneeCompanyBusinessUnits($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits());

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $this->tester->getFacade()->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertSame(1, $this->tester->getMerchantRelationshipQuery()->count());
        $this->assertSame(2, $this->tester->getMerchantRelationshipToCompanyBusinessUnitQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldCreateSplittedMerchantRelationshipsInPersistence(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setIsSplitEnabled(true)
            ->setStatus(static::STATUS_APPROVED)
            ->setAssigneeCompanyBusinessUnits($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits());

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $this->tester->getFacade()->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertSame(2, $this->tester->getMerchantRelationshipQuery()->count());
    }

    /**
     * @return void
     */
    public function testShouldThrowMerchantRelationshipNotCreatedException(): void
    {
        // Arrange
        $this->tester->setDependency(
            MerchantRelationRequestDependencyProvider::FACADE_MERCHANT_RELATIONSHIP,
            $this->createMerchantRelationshipFacadeMock(),
        );

        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_APPROVED)
            ->setAssigneeCompanyBusinessUnits($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits());

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Assert
        $this->expectException(MerchantRelationshipNotCreatedException::class);

        // Act
        $this->tester->getFacade()->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testMerchantRelationRequestUpdateStrategyNotFoundException(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest('not_existing_status');
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus('not_existing_status')
            ->setAssigneeCompanyBusinessUnits($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits());

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Assert
        $this->expectException(MerchantRelationRequestUpdateStrategyNotFoundException::class);

        // Act
        $this->tester->getFacade()->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldReturnUpdatedMerchantRelationRequest(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_APPROVED)
            ->setIsSplitEnabled(true)
            ->setDecisionNote('Fake Decision Note')
            ->addAssigneeCompanyBusinessUnit($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits()->offsetGet(0));

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertUpdatedMerchantRelationRequest(
            $merchantRelationRequest,
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
        $this->tester->getFacade()->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);
    }

    /**
     * @return void
     */
    public function testShouldValidateDecisionNoteLengthValidatorRule(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setDecisionNote($this->tester->generateRandomString(5001))
            ->setStatus(static::STATUS_REJECTED);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_DECISION_NOTE_WRONG_LENGTH,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateIsApprovableRequestValidatorRuleWithFakeUuid(): void
    {
        // Arrange
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid(static::FAKE_UUID)
            ->setStatus(static::STATUS_APPROVED);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_REQUEST_NOT_FOUND,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateIsApprovableRequestValidatorRuleWithEmptyAssigneeCompanyBusinessUnits(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_APPROVED)
            ->setAssigneeCompanyBusinessUnits(new ArrayObject());

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_REQUEST_CANT_BE_APPROVED,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateIsApprovableRequestValidatorRuleNotInPendingStatus(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_REJECTED);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_APPROVED)
            ->setAssigneeCompanyBusinessUnits($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits());

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_REQUEST_CANT_BE_APPROVED,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateIsApprovableRequestValidatorRuleWithAddedAdditionalAssigneeBusinessUnit(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_REJECTED);
        $newCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany([CompanyTransfer::IS_ACTIVE => true])->getIdCompany(),
        ]);

        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_APPROVED)
            ->setAssigneeCompanyBusinessUnits($merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits())
            ->addAssigneeCompanyBusinessUnit($newCompanyBusinessUnit);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_REQUEST_CANT_BE_APPROVED,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateIsApprovableRequestValidatorRuleWithNewAssigneeBusinessUnit(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_REJECTED);
        $newCompanyBusinessUnit = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $this->tester->haveCompany([CompanyTransfer::IS_ACTIVE => true])->getIdCompany(),
        ]);

        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_APPROVED)
            ->addAssigneeCompanyBusinessUnit($newCompanyBusinessUnit);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_REQUEST_CANT_BE_APPROVED,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateIsRejectableRequestValidatorRuleWithFakeUuid(): void
    {
        // Arrange
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid(static::FAKE_UUID)
            ->setStatus(static::STATUS_REJECTED);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_REQUEST_NOT_FOUND,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateIsRejectableRequestValidatorRuleNotInPendingStatus(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_CANCELED);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_REJECTED);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_REQUEST_CANT_BE_REJECTED,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateIsCancelableRequestValidatorRuleWithFakeUuid(): void
    {
        // Arrange
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid(static::FAKE_UUID)
            ->setStatus(static::STATUS_CANCELED);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_REQUEST_NOT_FOUND,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateIsCancelableRequestValidatorRuleNotInPendingStatus(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_REJECTED);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_CANCELED);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_REQUEST_CANT_BE_CANCELED,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateIsAllowedToUpdateToPendingValidatorRuleWithFakeUuid(): void
    {
        // Arrange
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid(static::FAKE_UUID)
            ->setStatus(static::STATUS_PENDING);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_REQUEST_NOT_FOUND,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateIsAllowedToUpdateToPendingValidatorRuleNotInPendingStatus(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_REJECTED);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setStatus(static::STATUS_PENDING);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(
            static::GLOSSARY_KEY_REQUEST_CANT_BECOME_PENDING,
            $merchantRelationRequestCollectionResponseTransfer->getErrors()->offsetGet(0)->getMessage(),
        );
    }

    /**
     * @return void
     */
    public function testShouldValidateIsAllowedToUpdateToPendingValidatorRuleAssigneeBusinessUnitIsNOtCompatibleWithCompany(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany([CompanyTransfer::IS_ACTIVE => true]);
        $companyBusinessUnitTransfer = $this->tester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequestTransfer->addAssigneeCompanyBusinessUnit($companyBusinessUnitTransfer);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

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
    public function testShouldCollectAllValidationMessagesIntoOneResponse(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_REJECTED);
        $merchantRelationRequest = (new MerchantRelationRequestTransfer())
            ->setUuid($merchantRelationRequestTransfer->getUuid())
            ->setDecisionNote($this->tester->generateRandomString(5001))
            ->setStatus(static::STATUS_APPROVED);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequest]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(2, $merchantRelationRequestCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testShouldUpdateValidEntitiesWhenIsTransactionalFlagDisabled(): void
    {
        // Arrange
        $invalidMerchantRelationRequest = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $invalidMerchantRelationRequest
            ->setStatus(static::STATUS_REJECTED)
            ->setDecisionNote($this->tester->generateRandomString(5001));

        $validMerchantRelationRequest = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $validMerchantRelationRequest->setStatus(static::STATUS_REJECTED);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([
                $invalidMerchantRelationRequest,
                $validMerchantRelationRequest,
            ]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(1, $this->tester->getMerchantRelationRequestQuery()->filterByStatus(static::STATUS_PENDING)->count());
        $this->assertSame(1, $this->tester->getMerchantRelationRequestQuery()->filterByStatus(static::STATUS_REJECTED)->count());
    }

    /**
     * @return void
     */
    public function testShouldAvoidPersistingOfValidEntitiesWhenIsTransactionalFlagEnabled(): void
    {
        // Arrange
        $validMerchantRelationRequest = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $validMerchantRelationRequest->setStatus(static::STATUS_REJECTED);

        $invalidMerchantRelationRequest = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $invalidMerchantRelationRequest
            ->setStatus(static::STATUS_REJECTED)
            ->setDecisionNote($this->tester->generateRandomString(5001));

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([
                $validMerchantRelationRequest,
                $invalidMerchantRelationRequest,
            ]))
            ->setIsTransactional(true);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(1, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(2, $this->tester->getMerchantRelationRequestQuery()->filterByStatus(static::STATUS_PENDING)->count());
    }

    /**
     * @return void
     */
    public function testShouldAvoidPersistingOfNotValidEntities(): void
    {
        // Arrange
        $merchantRelationRequest1 = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequest1
            ->setStatus(static::STATUS_REJECTED)
            ->setDecisionNote($this->tester->generateRandomString(5001));

        $merchantRelationRequest2 = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequest2
            ->setStatus(static::STATUS_REJECTED)
            ->setDecisionNote($this->tester->generateRandomString(5001));

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([
                $merchantRelationRequest1,
                $merchantRelationRequest2,
            ]))
            ->setIsTransactional(false);

        // Act
        $merchantRelationRequestCollectionResponseTransfer = $this->tester->getFacade()
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);

        // Assert
        $this->assertCount(2, $merchantRelationRequestCollectionResponseTransfer->getErrors());
        $this->assertSame(2, $this->tester->getMerchantRelationRequestQuery()->filterByStatus(static::STATUS_PENDING)->count());
    }

    /**
     * @return void
     */
    public function testShouldExecuteMerchantRelationRequestPostUpdatePluginStack(): void
    {
        // Assert
        $merchantRelationRequestPostUpdatePluginMock = $this->createMerchantRelationRequestPostUpdatePluginMock();

        // Arrange
        $this->tester->setDependency(
            MerchantRelationRequestDependencyProvider::PLUGINS_MERCHANT_RELATION_REQUEST_POST_UPDATE,
            [$merchantRelationRequestPostUpdatePluginMock],
        );

        $merchantRelationRequestTransfer = $this->tester->createStatusSpecificRequest(static::STATUS_PENDING);
        $merchantRelationRequestTransfer->setStatus(static::STATUS_REJECTED);

        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->setMerchantRelationRequests(new ArrayObject([$merchantRelationRequestTransfer]))
            ->setIsTransactional(false);

        // Act
        $this->tester->getFacade()->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);
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
            'Requires `uuid` to be set.' => [
                (new MerchantRelationRequestCollectionRequestTransfer())
                    ->setIsTransactional(false)
                    ->setMerchantRelationRequests(new ArrayObject([
                        $this->createDummyMerchantRelationRequest([
                            MerchantRelationRequestTransfer::STATUS => 'pending',
                            MerchantRelationRequestTransfer::UUID => null,
                        ]),
                    ])),
                'Missing required property "uuid" for transfer Generated\Shared\Transfer\MerchantRelationRequestTransfer.',
            ],
            'Requires `status` to be set.' => [
                (new MerchantRelationRequestCollectionRequestTransfer())
                    ->setIsTransactional(false)
                    ->setMerchantRelationRequests(new ArrayObject([
                        $this->createDummyMerchantRelationRequest([
                            MerchantRelationRequestTransfer::STATUS => null,
                            MerchantRelationRequestTransfer::UUID => 'uuid',
                        ]),
                    ])),
                'Missing required property "status" for transfer Generated\Shared\Transfer\MerchantRelationRequestTransfer.',
            ],
            'Requires at least one `assigneeCompanyBusinessUnits` to be set.' => [
                (new MerchantRelationRequestCollectionRequestTransfer())
                    ->setIsTransactional(false)
                    ->setMerchantRelationRequests(new ArrayObject([
                        $this->createDummyMerchantRelationRequest([
                            MerchantRelationRequestTransfer::STATUS => 'pending',
                            MerchantRelationRequestTransfer::UUID => 'uuid',
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
            ->withAssigneeCompanyBusinessUnit()
            ->build();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return void
     */
    protected function assertUpdatedMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): void {
        /** @var \Generated\Shared\Transfer\MerchantRelationRequestTransfer $persistedRequest */
        $persistedRequest = $merchantRelationRequestCollectionResponseTransfer->getMerchantRelationRequests()->offsetGet(0);

        $this->assertSame($merchantRelationRequestTransfer->getUuid(), $persistedRequest->getUuid());
        $this->assertSame($merchantRelationRequestTransfer->getDecisionNote(), $persistedRequest->getDecisionNote());
        $this->assertSame($merchantRelationRequestTransfer->getIsSplitEnabled(), $persistedRequest->getIsSplitEnabled());
        $this->assertSame($merchantRelationRequestTransfer->getStatus(), $persistedRequest->getStatus());
        $this->assertSame(
            $merchantRelationRequestTransfer->getAssigneeCompanyBusinessUnits()->count(),
            $persistedRequest->getAssigneeCompanyBusinessUnits()->count(),
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostUpdatePluginInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMerchantRelationRequestPostUpdatePluginMock(): MerchantRelationRequestPostUpdatePluginInterface
    {
        $merchantRelationRequestPostUpdatePluginMock = $this
            ->getMockBuilder(MerchantRelationRequestPostUpdatePluginInterface::class)
            ->getMock();

        $merchantRelationRequestPostUpdatePluginMock
            ->expects($this->once())
            ->method('postUpdate');

        return $merchantRelationRequestPostUpdatePluginMock;
    }

    /**
     * @return \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantRelationshipFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMerchantRelationshipFacadeMock(): MerchantRelationRequestToMerchantRelationshipFacadeInterface
    {
        $merchantRelationshipFacadeMock = $this
            ->getMockBuilder(MerchantRelationRequestToMerchantRelationshipFacadeInterface::class)
            ->getMock();

        $merchantRelationshipFacadeMock
            ->method('createMerchantRelationship')
            ->willReturn((new MerchantRelationshipResponseTransfer())->setIsSuccessful(false));

        return $merchantRelationshipFacadeMock;
    }
}
