<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CommentMerchantRelationRequestConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\Comment\CommentDependencyProvider;
use Spryker\Zed\CommentUserConnector\Communication\Plugin\Comment\UserCommentAuthorValidationStrategyPlugin;
use SprykerTest\Zed\CommentMerchantRelationRequestConnector\CommentMerchantRelationRequestConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CommentMerchantRelationRequestConnector
 * @group Business
 * @group Facade
 * @group ExpandMerchantRelationRequestCollectionWithCommentThreadTest
 * Add your own group annotations below this line
 */
class ExpandMerchantRelationRequestCollectionWithCommentThreadTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CommentMerchantRelationRequestConnector\CommentMerchantRelationRequestConnectorBusinessTester
     */
    protected CommentMerchantRelationRequestConnectorBusinessTester $tester;

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
    public function testShouldExpandRequestWithCommentThread(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createMerchantRelationRequest();
        $commentThreadTransfer = $this->tester->addUserCommentToMerchantRelationRequest(
            $merchantRelationRequestTransfer,
        );

        $merchantRelationRequestCollectionTransfer = (new MerchantRelationRequestCollectionTransfer())
            ->addMerchantRelationRequest($merchantRelationRequestTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationRequestCollectionWithCommentThread($merchantRelationRequestCollectionTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\CommentThreadTransfer $persistedCommentThread */
        $persistedCommentThread = $merchantRelationRequestCollectionTransfer
            ->getMerchantRelationRequests()
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
    public function testShouldExpandRequestWithCustomerComment(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createMerchantRelationRequest();
        $this->tester->addCustomerCommentToMerchantRelationRequest($merchantRelationRequestTransfer);

        $merchantRelationRequestCollectionTransfer = (new MerchantRelationRequestCollectionTransfer())
            ->addMerchantRelationRequest($merchantRelationRequestTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationRequestCollectionWithCommentThread($merchantRelationRequestCollectionTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequest */
        $merchantRelationRequest = $merchantRelationRequestCollectionTransfer
            ->getMerchantRelationRequests()
            ->getIterator()
            ->current();

        $this->assertNotNull($merchantRelationRequest->getCommentThread());
    }

    /**
     * @return void
     */
    public function testShouldExpandRequestWithUserComment(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createMerchantRelationRequest();
        $this->tester->addUserCommentToMerchantRelationRequest($merchantRelationRequestTransfer);

        $merchantRelationRequestCollectionTransfer = (new MerchantRelationRequestCollectionTransfer())
            ->addMerchantRelationRequest($merchantRelationRequestTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationRequestCollectionWithCommentThread($merchantRelationRequestCollectionTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequest */
        $merchantRelationRequest = $merchantRelationRequestCollectionTransfer
            ->getMerchantRelationRequests()
            ->getIterator()
            ->current();

        $this->assertNotNull($merchantRelationRequest->getCommentThread());
    }

    /**
     * @return void
     */
    public function testShouldExpandRequestWithCommentThreadOnlyForOneRequest(): void
    {
        // Arrange
        $merchantRelationRequest1 = $this->tester->createMerchantRelationRequest();
        $merchantRelationRequest2 = $this->tester->createMerchantRelationRequest();
        $this->tester->addUserCommentToMerchantRelationRequest($merchantRelationRequest1);

        $merchantRelationRequestCollectionTransfer = (new MerchantRelationRequestCollectionTransfer())
            ->addMerchantRelationRequest($merchantRelationRequest1)
            ->addMerchantRelationRequest($merchantRelationRequest2);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationRequestCollectionWithCommentThread($merchantRelationRequestCollectionTransfer);

        // Assert
        $this->assertNotNull(
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->offsetGet(0)->getCommentThread(),
        );
        $this->assertNull(
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->offsetGet(1)->getCommentThread(),
        );
    }

    /**
     * @return void
     */
    public function testShouldSkipExpansionForRequestsWithoutCommentThreads(): void
    {
        // Arrange
        $merchantRelationRequest1 = $this->tester->createMerchantRelationRequest();
        $merchantRelationRequest2 = $this->tester->createMerchantRelationRequest();

        $merchantRelationRequestCollectionTransfer = (new MerchantRelationRequestCollectionTransfer())
            ->addMerchantRelationRequest($merchantRelationRequest1)
            ->addMerchantRelationRequest($merchantRelationRequest2);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationRequestCollectionWithCommentThread($merchantRelationRequestCollectionTransfer);

        // Assert
        $this->assertNull(
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->offsetGet(0)->getCommentThread(),
        );
        $this->assertNull(
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->offsetGet(1)->getCommentThread(),
        );
    }

    /**
     * @return void
     */
    public function testShouldSkipExpansionWhenEmptyCollection(): void
    {
        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationRequestCollectionWithCommentThread(new MerchantRelationRequestCollectionTransfer());

        // Assert
        $this->assertEmpty($merchantRelationRequestCollectionTransfer->getMerchantRelationRequests());
    }

    /**
     * @return void
     */
    public function testThrowNullValueExceptionWhenMerchantRelationRequestIdNotProvided(): void
    {
        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->expandMerchantRelationRequestCollectionWithCommentThread(
                (new MerchantRelationRequestCollectionTransfer())
                    ->addMerchantRelationRequest(new MerchantRelationRequestTransfer()),
            );
    }

    /**
     * @return void
     */
    public function testShouldSkipExpansionWhenCommentThreadWithWrongOwnerType(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createMerchantRelationRequest();

        $commentTransfer = (new CommentBuilder([
            CommentTransfer::FK_USER => $this->tester->haveUser()->getIdUser(),
        ]))->build();
        $this->tester->haveComment([
            CommentRequestTransfer::COMMENT => $commentTransfer->toArray(),
            CommentRequestTransfer::OWNER_TYPE => 'dummy-owner-type',
            CommentRequestTransfer::OWNER_ID => $merchantRelationRequestTransfer->getIdMerchantRelationRequest(),
        ]);

        $merchantRelationRequestCollectionTransfer = (new MerchantRelationRequestCollectionTransfer())
            ->addMerchantRelationRequest($merchantRelationRequestTransfer);

        // Act
        $merchantRelationRequestCollectionTransfer = $this->tester
            ->getFacade()
            ->expandMerchantRelationRequestCollectionWithCommentThread($merchantRelationRequestCollectionTransfer);

        // Assert
        $this->assertNull(
            $merchantRelationRequestCollectionTransfer->getMerchantRelationRequests()->offsetGet(0)->getCommentThread(),
        );
    }
}
