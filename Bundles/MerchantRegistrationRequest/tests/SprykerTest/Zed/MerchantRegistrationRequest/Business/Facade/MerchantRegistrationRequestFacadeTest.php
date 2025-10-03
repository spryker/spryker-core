<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantRegistrationRequest\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\DataBuilder\MerchantRegistrationRequestBuilder;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Comment\CommentDependencyProvider;
use Spryker\Zed\CommentUserConnector\Communication\Plugin\Comment\UserCommentAuthorValidationStrategyPlugin;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig;
use SprykerTest\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantRegistrationRequest
 * @group Business
 * @group Facade
 * @group Facade
 * @group MerchantRegistrationRequestFacadeTest
 * Add your own group annotations below this line
 */
class MerchantRegistrationRequestFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_EMAIL_ALREARY_EXISTS = 'merchant_registration_request.error.email_already_exists';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_COMPANY_NAME_ALREADY_EXISTS = 'merchant_registration_request.error.company_name_already_exists';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MERCHANT_CANNOT_BE_ACCEPTED = 'merchant_registration_request.error.merchant_cannot_be_accepted';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_MERCHANT_CANNOT_BE_REJECTED = 'merchant_registration_request.error.merchant_cannot_be_rejected';

    /**
     * @uses \Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig::COMMENT_THREAD_MERCHANT_REGISTRATION_REQUEST_OWNER_TYPE
     *
     * @var string
     */
    protected const COMMENT_THREAD_MERCHANT_REGISTRATION_REQUEST_OWNER_TYPE = 'merchant_registration_request';

    protected MerchantRegistrationRequestBusinessTester $tester;

    protected StoreTransfer $storeTransfer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->storeTransfer = $this->tester->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $this->tester->setDependency(CommentDependencyProvider::PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY, [
            new UserCommentAuthorValidationStrategyPlugin(),
        ]);
    }

    public function testCreateMerchantRegistrationRequestSuccess(): void
    {
        // Act
        $merchantRegistrationResponseTransfer = $this->tester->getFacade()
            ->createMerchantRegistrationRequest($this->createMerchantRegistrationRequestTransfer());

        // Assert
        $this->assertTrue($merchantRegistrationResponseTransfer->getIsSuccess());
        $this->assertCount(0, $merchantRegistrationResponseTransfer->getErrors());
    }

    public function testCreateMerchantRegistrationRequestErrorEmailWithCompanyNameExistInMerchantRegistrationRequestTable(): void
    {
        // Arrange
        $merchantRegistrationRequestTransfer = $this->createMerchantRegistrationRequestTransfer();
        $this->tester->getFacade()
            ->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer);

        // Act
        $merchantRegistrationResponseTransfer = $this->tester->getFacade()
            ->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer);

        // Assert
        $errorMessages = [];

        foreach ($merchantRegistrationResponseTransfer->getErrors() as $errorTransfer) {
            $errorMessages[] = $errorTransfer->getMessage();
        }

        $this->assertFalse($merchantRegistrationResponseTransfer->getIsSuccess());
        $this->assertCount(2, $merchantRegistrationResponseTransfer->getErrors());
        $this->assertContains(static::GLOSSARY_KEY_EMAIL_ALREARY_EXISTS, $errorMessages);
        $this->assertContains(static::GLOSSARY_KEY_COMPANY_NAME_ALREADY_EXISTS, $errorMessages);
    }

    public function testCreateMerchantRegistrationRequestErrorEmailWithCompanyNameExistInMerchantRegistrationRequestTableWithRejectedStatus(): void
    {
        // Arrange
        $merchantRegistrationRequestTransfer = $this->createMerchantRegistrationRequestTransfer()
            ->setStatus(MerchantRegistrationRequestConfig::STATUS_REJECTED);
        $this->tester->getFacade()
            ->createMerchantRegistrationRequest(clone $merchantRegistrationRequestTransfer);

        // Act
        $merchantRegistrationResponseTransfer = $this->tester->getFacade()
            ->createMerchantRegistrationRequest(clone $merchantRegistrationRequestTransfer);

        // Assert
        $this->assertTrue($merchantRegistrationResponseTransfer->getIsSuccess());
        $this->assertCount(0, $merchantRegistrationResponseTransfer->getErrors());
    }

    public function testCreateMerchantRegistrationRequestErrorEmailExistsInMerchantTable(): void
    {
        // Arrange
        $merchantRegistrationRequestTransfer = $this->createMerchantRegistrationRequestTransfer();
        $this->tester->haveMerchant([MerchantTransfer::EMAIL => $merchantRegistrationRequestTransfer->getEmail()]);

        // Act
        $merchantRegistrationResponseTransfer = $this->tester->getFacade()
            ->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer);

        // Assert
        $errorTransfer = $merchantRegistrationResponseTransfer->getErrors()->getIterator()->current();
        $this->assertFalse($merchantRegistrationResponseTransfer->getIsSuccess());
        $this->assertCount(1, $merchantRegistrationResponseTransfer->getErrors());
        $this->assertSame(static::GLOSSARY_KEY_EMAIL_ALREARY_EXISTS, $errorTransfer->getMessage());
    }

    public function testCreateMerchantRegistrationRequestErrorCompanyNameExistsInMerchantTable(): void
    {
        // Arrange
        $merchantRegistrationRequestTransfer = $this->createMerchantRegistrationRequestTransfer();
        $this->tester->haveMerchant([MerchantTransfer::NAME => $merchantRegistrationRequestTransfer->getCompanyName()]);

        // Act
        $merchantRegistrationResponseTransfer = $this->tester->getFacade()
            ->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer);

        // Assert
        $errorTransfer = $merchantRegistrationResponseTransfer->getErrors()->getIterator()->current();
        $this->assertFalse($merchantRegistrationResponseTransfer->getIsSuccess());
        $this->assertCount(1, $merchantRegistrationResponseTransfer->getErrors());
        $this->assertSame(static::GLOSSARY_KEY_COMPANY_NAME_ALREADY_EXISTS, $errorTransfer->getMessage());
    }

    public function testFindMerchantRegistrationRequestByIdSuccess(): void
    {
        // Arrange
        $merchantRegistrationRequestTransfer = $this->createMerchantRegistrationRequestTransfer();
        $merchantRegistrationResponseTransfer = $this->tester->getFacade()
            ->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer);
        $idMerchantRegistrationRequest = $merchantRegistrationResponseTransfer->getMerchantRegistrationRequest()
            ->getIdMerchantRegistrationRequest();

        // Act
        $foundMerchantRegistrationRequestTransfer = $this->tester->getFacade()
            ->findMerchantRegistrationRequestById($idMerchantRegistrationRequest);

        // Assert
        $this->assertNotNull($foundMerchantRegistrationRequestTransfer, 'Merchant registration request should be found.');
        $this->assertSame($idMerchantRegistrationRequest, $foundMerchantRegistrationRequestTransfer->getIdMerchantRegistrationRequest());
        $this->assertSame($merchantRegistrationRequestTransfer->getCompanyName(), $foundMerchantRegistrationRequestTransfer->getCompanyName());
        $this->assertSame($merchantRegistrationRequestTransfer->getEmail(), $foundMerchantRegistrationRequestTransfer->getEmail());
    }

    public function testFindMerchantRegistrationRequestByIdNotFound(): void
    {
        // Arrange
        $nonExistentId = 99999;

        // Act
        $foundMerchantRegistrationRequestTransfer = $this->tester->getFacade()
            ->findMerchantRegistrationRequestById($nonExistentId);

        // Assert
        $this->assertNull($foundMerchantRegistrationRequestTransfer);
    }

    public function testAcceptMerchantRegistrationRequestSuccess(): void
    {
        // Arrange
        $merchantRegistrationRequestTransfer = $this->tester->getFacade()
            ->createMerchantRegistrationRequest($this->createMerchantRegistrationRequestTransfer())
            ->getMerchantRegistrationRequest();

        // Act
        $merchantRegistrationResponseTransfer = $this->tester->getFacade()
            ->acceptMerchantRegistrationRequest(clone $merchantRegistrationRequestTransfer);

        // Assert
        $this->assertTrue($merchantRegistrationResponseTransfer->getIsSuccess());
        $this->assertNotNull($merchantRegistrationResponseTransfer->getMerchantRegistrationRequest(), 'Merchant registration request should be accepted.');
        $this->assertSame(MerchantRegistrationRequestConfig::STATUS_ACCEPTED, $merchantRegistrationResponseTransfer->getMerchantRegistrationRequest()->getStatus());
        $this->assertSame($merchantRegistrationRequestTransfer->getIdMerchantRegistrationRequest(), $merchantRegistrationResponseTransfer->getMerchantRegistrationRequest()->getIdMerchantRegistrationRequest());
    }

    public function testAcceptMerchantRegistrationRequestCannotBeAccepted(): void
    {
        // Arrange
        $merchantRegistrationRequestTransfer = $this->createMerchantRegistrationRequestTransfer()
            ->setStatus(MerchantRegistrationRequestConfig::STATUS_REJECTED);
        $merchantRegistrationRequestTransfer = $this->tester->getFacade()
            ->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer)
            ->getMerchantRegistrationRequest();

        // Act
        $merchantRegistrationResponseTransfer = $this->tester->getFacade()
            ->acceptMerchantRegistrationRequest(clone $merchantRegistrationRequestTransfer);

        // Assert
        $errorTransfer = $merchantRegistrationResponseTransfer->getErrors()->getIterator()->current();
        $this->assertFalse($merchantRegistrationResponseTransfer->getIsSuccess());
        $this->assertCount(1, $merchantRegistrationResponseTransfer->getErrors());
        $this->assertSame(static::GLOSSARY_KEY_MERCHANT_CANNOT_BE_ACCEPTED, $errorTransfer->getMessage());
    }

    public function testRejectMerchantRegistrationRequestSuccess(): void
    {
        // Arrange
        $merchantRegistrationRequestTransfer = $this->tester->getFacade()
            ->createMerchantRegistrationRequest($this->createMerchantRegistrationRequestTransfer())
            ->getMerchantRegistrationRequest();

        // Act
        $merchantRegistrationResponseTransfer = $this->tester->getFacade()
            ->rejectMerchantRegistrationRequest(clone $merchantRegistrationRequestTransfer);

        // Assert
        $this->assertTrue($merchantRegistrationResponseTransfer->getIsSuccess());
        $this->assertNotNull($merchantRegistrationResponseTransfer->getMerchantRegistrationRequest(), 'Merchant registration request should be rejected.');
        $this->assertSame(MerchantRegistrationRequestConfig::STATUS_REJECTED, $merchantRegistrationResponseTransfer->getMerchantRegistrationRequest()->getStatus());
        $this->assertSame($merchantRegistrationRequestTransfer->getIdMerchantRegistrationRequest(), $merchantRegistrationResponseTransfer->getMerchantRegistrationRequest()->getIdMerchantRegistrationRequest());
    }

    public function testRejectMerchantRegistrationRequestCannotBeRejected(): void
    {
        // Arrange
        $merchantRegistrationRequestTransfer = $this->createMerchantRegistrationRequestTransfer()
            ->setStatus(MerchantRegistrationRequestConfig::STATUS_ACCEPTED);
        $merchantRegistrationRequestTransfer = $this->tester->getFacade()
            ->createMerchantRegistrationRequest($merchantRegistrationRequestTransfer)
            ->getMerchantRegistrationRequest();

        // Act
        $merchantRegistrationResponseTransfer = $this->tester->getFacade()
            ->rejectMerchantRegistrationRequest(clone $merchantRegistrationRequestTransfer);

        // Assert
        $errorTransfer = $merchantRegistrationResponseTransfer->getErrors()->getIterator()->current();
        $this->assertFalse($merchantRegistrationResponseTransfer->getIsSuccess());
        $this->assertCount(1, $merchantRegistrationResponseTransfer->getErrors());
        $this->assertSame(static::GLOSSARY_KEY_MERCHANT_CANNOT_BE_REJECTED, $errorTransfer->getMessage());
    }

    public function testExpandMerchantRegistrationRequestWithCommentThread(): void
    {
        // Arrange
        $merchantRegistrationRequestTransfer = $this->tester->getFacade()
            ->createMerchantRegistrationRequest($this->createMerchantRegistrationRequestTransfer())
            ->getMerchantRegistrationRequest();
        $commentTransfer = (new CommentBuilder([
            CommentTransfer::FK_USER => $this->tester->haveUser()->getIdUser(),
        ]))->build();
        $this->tester->haveComment([
            CommentRequestTransfer::COMMENT => $commentTransfer->toArray(),
            CommentRequestTransfer::OWNER_TYPE => static::COMMENT_THREAD_MERCHANT_REGISTRATION_REQUEST_OWNER_TYPE,
            CommentRequestTransfer::OWNER_ID => $merchantRegistrationRequestTransfer->getIdMerchantRegistrationRequest(),
        ]);

        // Act
        $merchantRegistrationRequestTransfer = $this->tester->getFacade()
            ->expandMerchantRegistrationRequestWithCommentThread($merchantRegistrationRequestTransfer);

        // Assert
        $this->assertNotNull($merchantRegistrationRequestTransfer->getCommentThread());
        $this->assertCount(1, $merchantRegistrationRequestTransfer->getCommentThread()->getComments());
    }

    public function testExpandMerchantRegistrationRequestWithCommentThreadWithoutComments(): void
    {
        // Arrange
        $merchantRegistrationRequestTransfer = $this->tester->getFacade()
            ->createMerchantRegistrationRequest($this->createMerchantRegistrationRequestTransfer())
            ->getMerchantRegistrationRequest();

        // Act
        $merchantRegistrationRequestTransfer = $this->tester->getFacade()
            ->expandMerchantRegistrationRequestWithCommentThread($merchantRegistrationRequestTransfer);

        // Assert
        $this->assertNull($merchantRegistrationRequestTransfer->getCommentThread());
    }

    protected function createMerchantRegistrationRequestTransfer(): MerchantRegistrationRequestTransfer
    {
        return (new MerchantRegistrationRequestBuilder())
            ->build()
            ->setStore($this->storeTransfer);
    }
}
