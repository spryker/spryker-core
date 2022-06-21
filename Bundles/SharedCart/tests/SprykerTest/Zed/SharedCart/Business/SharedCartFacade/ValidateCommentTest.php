<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SharedCart\Business\SharedCartFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CommentValidationResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SharedCart\Communication\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\WriteSharedCartPermissionPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SharedCart
 * @group Business
 * @group SharedCartFacade
 * @group ValidateCommentTest
 * Add your own group annotations below this line
 */
class ValidateCommentTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SharedCart\Business\Validator\SharedCartCommentValidator::GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_ACCESS_DENIED = 'comment.validation.error.access_denied';

    /**
     * @uses \Spryker\Zed\SharedCart\Business\Validator\SharedCartCommentValidator::GLOSSARY_KEY_COMMENT_VALIDATION_COMPANY_USER_NOT_SET
     *
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_VALIDATION_COMPANY_USER_NOT_SET = 'comment.validation.error.company_user_not_set';

    /**
     * @uses \Spryker\Zed\SharedCart\Business\Validator\SharedCartCommentValidator::GLOSSARY_KEY_COMMENT_VALIDATION_OWNER_NOT_SET
     *
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_VALIDATION_OWNER_NOT_SET = 'comment.validation.error.owner_not_set';

    /**
     * @var string
     */
    protected const QUOTE_PERMISSION_GROUP_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * @var string
     */
    protected const TEST_CUSTOMER_REFERENCE = 'test-ref';

    /**
     * @var \SprykerTest\Zed\SharedCart\SharedCartBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testWillReturnErrorIfCommentIsNotSet(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestTransfer());
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError(
            $commentValidationResponseTransfer,
            static::GLOSSARY_KEY_COMMENT_VALIDATION_COMPANY_USER_NOT_SET,
        );
    }

    /**
     * @return void
     */
    public function testWillReturnErrorIfCustomerIsNotSet(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestTransfer())->setComment((new CommentTransfer()));
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError(
            $commentValidationResponseTransfer,
            static::GLOSSARY_KEY_COMMENT_VALIDATION_COMPANY_USER_NOT_SET,
        );
    }

    /**
     * @return void
     */
    public function testWillReturnErrorIfCompanyUserIsNotSet(): void
    {
        // Arrange
        $commentTransfer = (new CommentTransfer())->setCustomer((new CustomerTransfer()));
        $commentRequestTransfer = (new CommentRequestTransfer())->setComment($commentTransfer);
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError(
            $commentValidationResponseTransfer,
            static::GLOSSARY_KEY_COMMENT_VALIDATION_COMPANY_USER_NOT_SET,
        );
    }

    /**
     * @return void
     */
    public function testWillReturnErrorIfIdCompanyUserIsNotSet(): void
    {
        // Arrange
        $customerTransfer = (new CustomerTransfer())->setCompanyUserTransfer(new CompanyUserTransfer());
        $commentTransfer = (new CommentTransfer())->setCustomer($customerTransfer);
        $commentRequestTransfer = (new CommentRequestTransfer())->setComment($commentTransfer);
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError(
            $commentValidationResponseTransfer,
            static::GLOSSARY_KEY_COMMENT_VALIDATION_COMPANY_USER_NOT_SET,
        );
    }

    /**
     * @return void
     */
    public function testWillReturnErrorIfOwnerIsNotSet(): void
    {
        // Arrange
        $companyUserTransfer = (new CompanyUserTransfer())->setIdCompanyUser(1);
        $customerTransfer = (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer);
        $commentTransfer = (new CommentTransfer())->setCustomer($customerTransfer);
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerType(static::TEST_CUSTOMER_REFERENCE)
            ->setComment($commentTransfer);
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError(
            $commentValidationResponseTransfer,
            static::GLOSSARY_KEY_COMMENT_VALIDATION_OWNER_NOT_SET,
        );
    }

    /**
     * @return void
     */
    public function testWillReturnErrorIfQuoteDoesNotExist(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $customerTransfer = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $customerTransfer = (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer);
        $commentTransfer = (new CommentTransfer())->setCustomer($customerTransfer);

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setComment($commentTransfer)
            ->setOwnerId(0);
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError($commentValidationResponseTransfer, static::GLOSSARY_KEY_COMMENT_ACCESS_DENIED);
    }

    /**
     * @return void
     */
    public function testWillReturnErrorIfCompanyUserDoeNotHavePermissionForProvidedQuote(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $customerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER => $customerTransfer,
        ]);
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $customerTransfer = (new CustomerTransfer())->setCompanyUserTransfer($companyUserTransfer);
        $commentTransfer = (new CommentTransfer())->setCustomer($customerTransfer);
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($quoteTransfer->getIdQuote())
            ->setComment($commentTransfer);
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertValidationError($commentValidationResponseTransfer, static::GLOSSARY_KEY_COMMENT_ACCESS_DENIED);
    }

    /**
     * @return void
     */
    public function testWillReturnSuccessfulResponseIfCompanyUserHasPermissionForProvidedQuote(): void
    {
        // Arrange
        $companyTransfer = $this->tester->haveCompany();
        $ownerCustomerTransfer = $this->tester->haveCustomer();
        $quoteTransfer = $this->tester->havePersistentQuote([
            QuoteTransfer::CUSTOMER_REFERENCE => $ownerCustomerTransfer->getIdCustomer(),
            QuoteTransfer::CUSTOMER => $ownerCustomerTransfer,
        ]);

        $customerTransfer = $this->tester->haveCustomer();
        $companyUserTransfer = $this->tester->haveCompanyUser([
            CompanyUserTransfer::FK_CUSTOMER => $customerTransfer->getIdCustomer(),
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
        ]);
        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);

        $fullAccessQuotePermissionGroup = $this->tester->haveQuotePermissionGroup(static::QUOTE_PERMISSION_GROUP_FULL_ACCESS, [
            ReadSharedCartPermissionPlugin::KEY,
            WriteSharedCartPermissionPlugin::KEY,
        ]);

        $this->tester->haveQuoteCompanyUser(
            $companyUserTransfer,
            $quoteTransfer,
            $fullAccessQuotePermissionGroup,
        );

        $commentTransfer = (new CommentTransfer())->setCustomer($customerTransfer);
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($quoteTransfer->getIdQuote())
            ->setComment($commentTransfer);
        $commentValidationResponseTransfer = (new CommentValidationResponseTransfer());

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartComment(
            $commentRequestTransfer,
            $commentValidationResponseTransfer,
        );

        // Assert
        $this->assertTrue($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertCount(0, $commentValidationResponseTransfer->getMessages());
    }

    /**
     * @param \Generated\Shared\Transfer\CommentValidationResponseTransfer $commentValidationResponseTransfer
     * @param string $expectedMessage
     *
     * @return void
     */
    protected function assertValidationError(CommentValidationResponseTransfer $commentValidationResponseTransfer, string $expectedMessage): void
    {
        $this->assertFalse($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $commentValidationResponseTransfer->getMessages());
        $this->assertSame($expectedMessage, $commentValidationResponseTransfer->getMessages()->offsetGet(0)->getValue());
    }
}
