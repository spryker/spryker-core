<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CommentMerchantRelationshipConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\Comment\CommentDependencyProvider;
use Spryker\Zed\CommentUserConnector\Communication\Plugin\Comment\UserCommentAuthorValidationStrategyPlugin;
use SprykerTest\Zed\CommentMerchantRelationshipConnector\CommentMerchantRelationshipConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CommentMerchantRelationshipConnector
 * @group Business
 * @group Facade
 * @group ExpandMerchantRelationshipCollectionWithCommentThreadTest
 * Add your own group annotations below this line
 */
class ExpandMerchantRelationshipCollectionWithCommentThreadTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CommentMerchantRelationshipConnector\CommentMerchantRelationshipConnectorBusinessTester
     */
    protected CommentMerchantRelationshipConnectorBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(CommentDependencyProvider::PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY, [
            new UserCommentAuthorValidationStrategyPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testShouldExpandRelationshipWithCommentThread(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $commentThreadTransfer = $this->tester->addUserCommentToMerchantRelationship(
            $merchantRelationshipTransfer,
        );

        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationshipCollectionWithCommentThread($merchantRelationshipCollectionTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\CommentThreadTransfer $persistedCommentThread */
        $persistedCommentThread = $merchantRelationshipCollectionTransfer
            ->getMerchantRelationships()
            ->getIterator()
            ->current()
            ->getCommentThread();

        $this->assertSame($commentThreadTransfer->getIdCommentThread(), $persistedCommentThread->getIdCommentThread());
        $this->assertSame($commentThreadTransfer->getUuid(), $persistedCommentThread->getUuid());
        $this->assertSame($commentThreadTransfer->getOwnerId(), $persistedCommentThread->getOwnerId());
        $this->assertSame($commentThreadTransfer->getOwnerType(), $persistedCommentThread->getOwnerType());
        $this->assertCount($commentThreadTransfer->getComments()->count(), $persistedCommentThread->getComments());
    }

    /**
     * @return void
     */
    public function testShouldExpandRelationshipWithCustomerComment(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $this->tester->addCustomerCommentToMerchantRelationship($merchantRelationshipTransfer);

        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationshipCollectionWithCommentThread($merchantRelationshipCollectionTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationship */
        $merchantRelationship = $merchantRelationshipCollectionTransfer
            ->getMerchantRelationships()
            ->getIterator()
            ->current();

        $this->assertNotNull($merchantRelationship->getCommentThread());
    }

    /**
     * @return void
     */
    public function testShouldExpandRelationshipWithUserComment(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $this->tester->addUserCommentToMerchantRelationship($merchantRelationshipTransfer);

        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationshipCollectionWithCommentThread($merchantRelationshipCollectionTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationship */
        $merchantRelationship = $merchantRelationshipCollectionTransfer
            ->getMerchantRelationships()
            ->getIterator()
            ->current();

        $this->assertNotNull($merchantRelationship->getCommentThread());
    }

    /**
     * @return void
     */
    public function testShouldExpandRelationshipWithCommentThreadOnlyForOneRelationship(): void
    {
        // Arrange
        $merchantRelationship1 = $this->tester->createMerchantRelationship();
        $merchantRelationship2 = $this->tester->createMerchantRelationship();
        $this->tester->addUserCommentToMerchantRelationship($merchantRelationship1);

        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationship1)
            ->addMerchantRelationship($merchantRelationship2);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationshipCollectionWithCommentThread($merchantRelationshipCollectionTransfer);

        // Assert
        $this->assertNotNull(
            $merchantRelationshipCollectionTransfer->getMerchantRelationships()->offsetGet(0)->getCommentThread(),
        );
        $this->assertNull(
            $merchantRelationshipCollectionTransfer->getMerchantRelationships()->offsetGet(1)->getCommentThread(),
        );
    }

    /**
     * @return void
     */
    public function testShouldSkipExpansionForRequestsWithoutCommentThreads(): void
    {
        // Arrange
        $merchantRelationship1 = $this->tester->createMerchantRelationship();
        $merchantRelationship2 = $this->tester->createMerchantRelationship();

        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationship1)
            ->addMerchantRelationship($merchantRelationship2);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationshipCollectionWithCommentThread($merchantRelationshipCollectionTransfer);

        // Assert
        $this->assertNull(
            $merchantRelationshipCollectionTransfer->getMerchantRelationships()->offsetGet(0)->getCommentThread(),
        );
        $this->assertNull(
            $merchantRelationshipCollectionTransfer->getMerchantRelationships()->offsetGet(1)->getCommentThread(),
        );
    }

    /**
     * @return void
     */
    public function testShouldSkipExpansionWhenEmptyCollection(): void
    {
        // Act
        $merchantRelationshipCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationshipCollectionWithCommentThread(new MerchantRelationshipCollectionTransfer());

        // Assert
        $this->assertEmpty($merchantRelationshipCollectionTransfer->getMerchantRelationships());
    }

    /**
     * @return void
     */
    public function testThrowNullValueExceptionWhenMerchantRelationshipIdNotProvided(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->expandMerchantRelationshipCollectionWithCommentThread(
                (new MerchantRelationshipCollectionTransfer())
                    ->addMerchantRelationship(new MerchantRelationshipTransfer()),
            );
    }

    /**
     * @return void
     */
    public function testShouldSkipExpansionWhenCommentThreadWithWrongOwnerType(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();

        $commentTransfer = (new CommentBuilder([
            CommentTransfer::FK_USER => $this->tester->haveUser()->getIdUser(),
        ]))->build();
        $this->tester->haveComment([
            CommentRequestTransfer::COMMENT => $commentTransfer->toArray(),
            CommentRequestTransfer::OWNER_TYPE => 'dummy-owner-type',
            CommentRequestTransfer::OWNER_ID => $merchantRelationshipTransfer->getIdMerchantRelationship(),
        ]);

        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationshipCollectionWithCommentThread($merchantRelationshipCollectionTransfer);

        // Assert
        $this->assertNull(
            $merchantRelationshipCollectionTransfer->getMerchantRelationships()->offsetGet(0)->getCommentThread(),
        );
    }
}
