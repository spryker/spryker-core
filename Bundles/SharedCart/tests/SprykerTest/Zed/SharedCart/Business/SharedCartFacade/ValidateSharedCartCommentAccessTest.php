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
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer;
use Spryker\Zed\SharedCart\Communication\Plugin\ReadSharedCartPermissionPlugin;
use Spryker\Zed\SharedCart\Communication\Plugin\WriteSharedCartPermissionPlugin;
use SprykerTest\Zed\SharedCart\SharedCartBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SharedCart
 * @group Business
 * @group SharedCartFacade
 * @group ValidateSharedCartCommentAccessTest
 * Add your own group annotations below this line
 */
class ValidateSharedCartCommentAccessTest extends Unit
{
    /**
     * @uses \Spryker\Zed\SharedCart\Business\Validator\SharedCartAccessCommentValidator::GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED = 'comment.validation.error.access_denied';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS
     *
     * @var string
     */
    protected const PERMISSION_GROUP_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * @uses \Spryker\Shared\SharedCart\SharedCartConfig::PERMISSION_GROUP_READ_ONLY
     *
     * @var string
     */
    protected const PERMISSION_GROUP_READ_ONLY = 'READ_ONLY';

    /**
     * @uses \Spryker\Zed\SharedCart\Communication\Plugin\Comment\SharedCartAccessCommentValidatorPlugin::COMMENT_THREAD_QUOTE_OWNER_TYPE
     *
     * @var string
     */
    protected const COMMENT_THREAD_QUOTE_OWNER_TYPE = 'quote';

    /**
     * @var \SprykerTest\Zed\SharedCart\SharedCartBusinessTester
     */
    protected SharedCartBusinessTester $tester;

    /**
     * @return void
     */
    public function testShouldReturnSuccessfulResponseIfCustomerHasAccessToQuoteWithFullAccess(): void
    {
        // Arrange
        [$quoteTransfer, $ownerCustomerTransfer, $customerTransfer] = $this->createSharedCartForTwoCustomers();

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($quoteTransfer->getIdQuote())
            ->setOwnerType(static::COMMENT_THREAD_QUOTE_OWNER_TYPE)
            ->setComment((new CommentTransfer())->setCustomer($customerTransfer));

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartCommentAccess(
            $commentRequestTransfer,
            new CommentValidationResponseTransfer(),
        );

        // Assert
        $this->assertTrue($commentValidationResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testShouldReturnSuccessfulResponseIfCustomerHasAccessToQuoteWithReadOnly(): void
    {
        // Arrange
        [$quoteTransfer, $ownerCustomerTransfer, $customerTransfer] = $this->createSharedCartForTwoCustomers(
            static::PERMISSION_GROUP_READ_ONLY,
        );

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($quoteTransfer->getIdQuote())
            ->setOwnerType(static::COMMENT_THREAD_QUOTE_OWNER_TYPE)
            ->setComment((new CommentTransfer())->setCustomer($customerTransfer));

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartCommentAccess(
            $commentRequestTransfer,
            new CommentValidationResponseTransfer(),
        );

        // Assert
        $this->assertTrue($commentValidationResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testShouldReturnSuccessfulResponseIfCustomerIsOwnerOfQuote(): void
    {
        // Arrange
        [$quoteTransfer, $ownerCustomerTransfer] = $this->createSharedCartForTwoCustomers();

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($quoteTransfer->getIdQuote())
            ->setOwnerType(static::COMMENT_THREAD_QUOTE_OWNER_TYPE)
            ->setComment((new CommentTransfer())->setCustomer($ownerCustomerTransfer));

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartCommentAccess(
            $commentRequestTransfer,
            new CommentValidationResponseTransfer(),
        );

        // Assert
        $this->assertTrue($commentValidationResponseTransfer->getIsSuccessful());
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorIfCustomerDoesNotHaveAccessToQuote(): void
    {
        // Arrange
        [$quoteTransfer] = $this->createSharedCartForTwoCustomers();
        $unknownCustomerTransfer = $this->tester->haveCustomer();

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($quoteTransfer->getIdQuote())
            ->setOwnerType(static::COMMENT_THREAD_QUOTE_OWNER_TYPE)
            ->setComment((new CommentTransfer())->setCustomer($unknownCustomerTransfer));

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartCommentAccess(
            $commentRequestTransfer,
            new CommentValidationResponseTransfer(),
        );

        // Assert
        $this->assertFalse($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED,
            $commentValidationResponseTransfer->getMessages()->getIterator()->current()->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorIfQuoteDoesNotExist(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId(123456)
            ->setOwnerType(static::COMMENT_THREAD_QUOTE_OWNER_TYPE)
            ->setComment((new CommentTransfer())->setCustomer($this->tester->haveCustomer()));

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartCommentAccess(
            $commentRequestTransfer,
            new CommentValidationResponseTransfer(),
        );

        // Assert
        $this->assertFalse($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED,
            $commentValidationResponseTransfer->getMessages()->getIterator()->current()->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorIfCompanyUserIsNotSet(): void
    {
        // Arrange
        [$quoteTransfer] = $this->createSharedCartForTwoCustomers();

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($quoteTransfer->getIdQuote())
            ->setOwnerType(static::COMMENT_THREAD_QUOTE_OWNER_TYPE)
            ->setComment((new CommentTransfer())->setCustomer(
                $this->tester->haveCustomer()->setCompanyUserTransfer(null),
            ));

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartCommentAccess(
            $commentRequestTransfer,
            new CommentValidationResponseTransfer(),
        );

        // Assert
        $this->assertFalse($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED,
            $commentValidationResponseTransfer->getMessages()->getIterator()->current()->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testShouldReturnErrorIfCompanyUserIdIsNotSet(): void
    {
        // Arrange
        [$quoteTransfer] = $this->createSharedCartForTwoCustomers();

        $commentRequestTransfer = (new CommentRequestTransfer())
            ->setOwnerId($quoteTransfer->getIdQuote())
            ->setOwnerType(static::COMMENT_THREAD_QUOTE_OWNER_TYPE)
            ->setComment((new CommentTransfer())->setCustomer(
                $this->tester->haveCustomer()->setCompanyUserTransfer((new CompanyUserTransfer())->setIdCompanyUser(null)),
            ));

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateSharedCartCommentAccess(
            $commentRequestTransfer,
            new CommentValidationResponseTransfer(),
        );

        // Assert
        $this->assertFalse($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertSame(
            static::GLOSSARY_KEY_COMMENT_VALIDATION_ACCESS_DENIED,
            $commentValidationResponseTransfer->getMessages()->getIterator()->current()->getValue(),
        );
    }

    /**
     * @param string $permissionGroup
     *
     * @return list<\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    protected function createSharedCartForTwoCustomers(string $permissionGroup = self::PERMISSION_GROUP_FULL_ACCESS): array
    {
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

        $this->tester->haveQuoteCompanyUser(
            $companyUserTransfer,
            $quoteTransfer,
            $this->createPermissionGroup($permissionGroup),
        );

        return [$quoteTransfer, $ownerCustomerTransfer, $customerTransfer];
    }

    /**
     * @param string $permissionGroup
     *
     * @return \Generated\Shared\Transfer\SpyQuotePermissionGroupEntityTransfer
     */
    protected function createPermissionGroup(string $permissionGroup): SpyQuotePermissionGroupEntityTransfer
    {
        if ($permissionGroup === static::PERMISSION_GROUP_READ_ONLY) {
            return $this->tester->haveQuotePermissionGroup(
                static::PERMISSION_GROUP_READ_ONLY,
                [
                    ReadSharedCartPermissionPlugin::KEY,
                ],
            );
        }

        return $this->tester->haveQuotePermissionGroup(
            static::PERMISSION_GROUP_FULL_ACCESS,
            [
                ReadSharedCartPermissionPlugin::KEY,
                WriteSharedCartPermissionPlugin::KEY,
            ],
        );
    }
}
