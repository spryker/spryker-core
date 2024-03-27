<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CommentUserConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\DataBuilder\CommentRequestBuilder;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\CommentUserConnector\Communication\Plugin\Comment\UserCommentAuthorValidationStrategyPlugin;
use SprykerTest\Zed\CommentUserConnector\CommentUserConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CommentUserConnector
 * @group Business
 * @group Facade
 * @group ValidateCommentAuthorTest
 * Add your own group annotations below this line
 */
class ValidateCommentAuthorTest extends Unit
{
    /**
     * @uses \Spryker\Zed\Comment\CommentDependencyProvider::PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY
     *
     * @var string
     */
    protected const PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY = 'PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY';

    /**
     * @uses \Spryker\Zed\CommentUserConnector\Business\Validator\CommentValidator::GLOSSARY_KEY_COMMENT_ACCESS_DENIED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_COMMENT_ACCESS_DENIED = 'comment.validation.error.access_denied';

    /**
     * @uses \Spryker\Zed\CommentUserConnector\Business\Validator\CommentValidator::GLOSSARY_KEY_USER_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_USER_NOT_FOUND = 'comment.validation.error.user_not_found';

    /**
     * @uses \Spryker\Zed\CommentUserConnector\Business\Validator\CommentValidator::GLOSSARY_KEY_AUTHOR_INTERSECTED
     *
     * @var string
     */
    protected const GLOSSARY_KEY_AUTHOR_INTERSECTED = 'comment.validation.error.comment_author_intersected';

    /**
     * @var \SprykerTest\Zed\CommentUserConnector\CommentUserConnectorBusinessTester
     */
    protected CommentUserConnectorBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(static::PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY, [
            new UserCommentAuthorValidationStrategyPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorWhenFkUserOfExistingUserIsProvided(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $commentRequestTransfer = (new CommentRequestBuilder())
            ->withComment([CommentTransfer::FK_USER => $userTransfer->getIdUserOrFail()])
            ->build();

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateCommentAuthor($commentRequestTransfer);

        // Assert
        $this->assertTrue($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertEmpty($commentValidationResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testReturnsNoErrorWhenFkUserIsProvidedAndCommentHasIdCommentSet(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();

        $commentTransfer = (new CommentBuilder([
            CommentTransfer::FK_USER => $userTransfer->getIdUserOrFail(),
        ]))->build();
        $commentThreadResponseTransfer = $this->tester->haveComment([
            CommentRequestTransfer::COMMENT => $commentTransfer->toArray(),
        ]);

        $commentTransfer = $commentThreadResponseTransfer->getCommentThreadOrFail()->getComments()->getIterator()->current();
        $commentRequestTransfer = (new CommentRequestBuilder())
            ->withComment($commentTransfer->toArray())
            ->build();

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateCommentAuthor($commentRequestTransfer);

        // Assert
        $this->assertTrue($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertEmpty($commentValidationResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenFkUserOfNonExistingUserIsProvided(): void
    {
        // Arrange
        $this->tester->haveUser();
        $commentRequestTransfer = (new CommentRequestBuilder())
            ->withComment([CommentTransfer::FK_USER => -1])
            ->build();

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateCommentAuthor($commentRequestTransfer);

        // Assert
        $this->assertFalse($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $commentValidationResponseTransfer->getMessages());
        $this->assertSame(
            static::GLOSSARY_KEY_USER_NOT_FOUND,
            $commentValidationResponseTransfer->getMessages()->getIterator()->current()->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenProvidedUserIsNotAnAuthorOfUpdatedComment(): void
    {
        // Arrange
        $user1Transfer = $this->tester->haveUser();
        $user2Transfer = $this->tester->haveUser();

        $commentTransfer = (new CommentBuilder([
            CommentTransfer::FK_USER => $user1Transfer->getIdUserOrFail(),
        ]))->build();
        $commentThreadResponseTransfer = $this->tester->haveComment([
            CommentRequestTransfer::COMMENT => $commentTransfer->toArray(),
        ]);

        $commentTransfer = $commentThreadResponseTransfer->getCommentThreadOrFail()->getComments()->getIterator()->current();
        $commentRequestTransfer = (new CommentRequestBuilder())
            ->withComment($commentTransfer->setFkUser($user2Transfer->getIdUserOrFail())->toArray())
            ->build();

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateCommentAuthor($commentRequestTransfer);

        // Assert
        $this->assertFalse($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $commentValidationResponseTransfer->getMessages());
        $this->assertSame(
            static::GLOSSARY_KEY_COMMENT_ACCESS_DENIED,
            $commentValidationResponseTransfer->getMessages()->getIterator()->current()->getValue(),
        );
    }

    /**
     * @return void
     */
    public function testReturnsErrorWhenCommentAuthorIntersected(): void
    {
        // Arrange
        $commentRequestTransfer = (new CommentRequestBuilder())
            ->withComment([CommentTransfer::FK_USER => $this->tester->haveUser()->getIdUserOrFail()])
            ->withComment([CommentTransfer::CUSTOMER => (new CustomerTransfer())->setIdCustomer(123)])
            ->build();

        // Act
        $commentValidationResponseTransfer = $this->tester->getFacade()->validateCommentAuthor($commentRequestTransfer);

        // Assert
        $this->assertFalse($commentValidationResponseTransfer->getIsSuccessful());
        $this->assertCount(1, $commentValidationResponseTransfer->getMessages());
        $this->assertSame(
            static::GLOSSARY_KEY_AUTHOR_INTERSECTED,
            $commentValidationResponseTransfer->getMessages()->getIterator()->current()->getValue(),
        );
    }

    /**
     * @dataProvider throwsNullValueExceptionWhenRequiredPropertyIsNotSetDataProvider
     *
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return void
     */
    public function testThrowsNullValueExceptionWhenRequiredPropertyIsNotSet(CommentRequestTransfer $commentRequestTransfer): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()->validateCommentAuthor($commentRequestTransfer);
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\CommentRequestTransfer>>
     */
    protected function throwsNullValueExceptionWhenRequiredPropertyIsNotSetDataProvider(): array
    {
        return [
            'CommentRequestTransfer.comment property is not set' => [
                (new CommentRequestBuilder([CommentRequestTransfer::COMMENT => null]))->build(),
            ],
            'CommentRequestTransfer.comment.fkUser property is not set' => [
                (new CommentRequestBuilder([
            CommentRequestTransfer::COMMENT => [
                    CommentTransfer::FK_USER => null,
                ]]))->build(),
            ],
        ];
    }
}
