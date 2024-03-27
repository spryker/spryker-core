<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CommentMerchantRelationRequestConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\Comment\CommentDependencyProvider;
use Spryker\Zed\CommentMerchantRelationRequestConnector\Communication\Plugin\MerchantRelationRequest\CommentThreadMerchantRelationRequestExpanderPlugin;
use Spryker\Zed\CommentUserConnector\Communication\Plugin\Comment\UserCommentAuthorValidationStrategyPlugin;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestDependencyProvider;
use SprykerTest\Zed\CommentMerchantRelationRequestConnector\CommentMerchantRelationRequestConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CommentMerchantRelationRequestConnector
 * @group Business
 * @group Facade
 * @group CopyCommentThreadsFromMerchantRelationRequestsTest
 * Add your own group annotations below this line
 */
class CopyCommentThreadsFromMerchantRelationRequestsTest extends Unit
{
    /**
     * @uses \Spryker\Zed\CommentMerchantRelationshipConnector\CommentMerchantRelationshipConnectorConfig::COMMENT_THREAD_MERCHANT_RELATIONSHIP_OWNER_TYPE
     *
     * @var string
     */
    protected const COMMENT_THREAD_MERCHANT_RELATIONSHIP_OWNER_TYPE = 'merchant_relationship';

    /**
     * @var string
     */
    protected const FAKE_UUID = 'FAKE_UUID';

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

        $this->tester->setDependency(MerchantRelationRequestDependencyProvider::PLUGINS_MERCHANT_RELATION_REQUEST_EXPANDER, [
            new CommentThreadMerchantRelationRequestExpanderPlugin(),
        ]);
    }

    /**
     * @return void
     */
    public function testShouldCopyCommentThreadFromRequestToRelationship(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createMerchantRelationRequest();
        $commentThreadTransfer = $this->tester->addUserCommentToMerchantRelationRequest(
            $merchantRelationRequestTransfer,
        );
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship(
            $merchantRelationRequestTransfer->getUuid(),
        );

        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester
            ->getFacade()
            ->copyCommentThreadsFromMerchantRelationRequests($merchantRelationshipCollectionTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\CommentThreadTransfer $copiedCommentThread */
        $copiedCommentThread = $merchantRelationshipCollectionTransfer
            ->getMerchantRelationships()
            ->getIterator()
            ->current()
            ->getCommentThread();

        $this->assertNotSame($commentThreadTransfer->getIdCommentThread(), $copiedCommentThread->getIdCommentThread());
        $this->assertNotSame($commentThreadTransfer->getUuid(), $copiedCommentThread->getUuid());
        $this->assertNotSame($commentThreadTransfer->getOwnerType(), $copiedCommentThread->getOwnerType());
        $this->assertSame($merchantRelationshipTransfer->getIdMerchantRelationship(), $copiedCommentThread->getOwnerId());
        $this->assertSame(static::COMMENT_THREAD_MERCHANT_RELATIONSHIP_OWNER_TYPE, $copiedCommentThread->getOwnerType());
    }

    /**
     * @return void
     */
    public function testShouldNotCopyCommentThreadWhenRequestUuidWasAbsent(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship();
        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester
            ->getFacade()
            ->copyCommentThreadsFromMerchantRelationRequests($merchantRelationshipCollectionTransfer);

        // Assert
        $this->assertNull(
            $merchantRelationshipCollectionTransfer->getMerchantRelationships()->getIterator()->current()->getCommentThread(),
        );
    }

    /**
     * @return void
     */
    public function testShouldNotCopyCommentThreadWhenRequestWithoutCommentThread(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createMerchantRelationRequest();
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship(
            $merchantRelationRequestTransfer->getUuid(),
        );

        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester
            ->getFacade()
            ->copyCommentThreadsFromMerchantRelationRequests($merchantRelationshipCollectionTransfer);

        // Assert
        $this->assertNull(
            $merchantRelationshipCollectionTransfer->getMerchantRelationships()->getIterator()->current()->getCommentThread(),
        );
    }

    /**
     * @return void
     */
    public function testShouldNotCopyCommentThreadWhenRequestWasNotFoundInPersistence(): void
    {
        // Arrange
        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship(static::FAKE_UUID);
        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationshipTransfer);

        // Act
        $merchantRelationshipCollectionTransfer = $this->tester
            ->getFacade()
            ->copyCommentThreadsFromMerchantRelationRequests($merchantRelationshipCollectionTransfer);

        // Assert
        $this->assertNull(
            $merchantRelationshipCollectionTransfer->getMerchantRelationships()->getIterator()->current()->getCommentThread(),
        );
    }

    /**
     * @return void
     */
    public function testThrowNullValueExceptionWhenIdMerchantRelationshipNotProvided(): void
    {
        // Arrange
        $merchantRelationRequestTransfer = $this->tester->createMerchantRelationRequest();
        $this->tester->addUserCommentToMerchantRelationRequest($merchantRelationRequestTransfer);

        $merchantRelationshipTransfer = $this->tester->createMerchantRelationship(
            $merchantRelationRequestTransfer->getUuid(),
        )->setIdMerchantRelationship(null);

        $merchantRelationshipCollectionTransfer = (new MerchantRelationshipCollectionTransfer())
            ->addMerchantRelationship($merchantRelationshipTransfer);

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester
            ->getFacade()
            ->copyCommentThreadsFromMerchantRelationRequests($merchantRelationshipCollectionTransfer);
    }
}
