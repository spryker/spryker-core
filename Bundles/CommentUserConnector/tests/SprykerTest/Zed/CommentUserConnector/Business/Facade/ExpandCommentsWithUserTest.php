<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CommentUserConnector\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CommentBuilder;
use Generated\Shared\Transfer\CommentTransfer;
use SprykerTest\Zed\CommentUserConnector\CommentUserConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CommentUserConnector
 * @group Business
 * @group Facade
 * @group ExpandCommentsWithUserTest
 * Add your own group annotations below this line
 */
class ExpandCommentsWithUserTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CommentUserConnector\CommentUserConnectorBusinessTester
     */
    protected CommentUserConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandsCommentWithUserTransferWhenFkUserIsSet(): void
    {
        // Arrange
        $userTransfer = $this->tester->haveUser();
        $commentTransfer = (new CommentBuilder([
            CommentTransfer::FK_USER => $userTransfer->getIdUserOrFail(),
        ]))->build();

        // Act
        $commentTransfers = $this->tester->getFacade()->expandCommentsWithUser([$commentTransfer]);

        // Assert
        $this->assertCount(1, $commentTransfers);
        $this->assertNotNull($commentTransfers[0]->getUser());
        $this->assertSame($userTransfer->toArray(), $commentTransfers[0]->getUserOrFail()->toArray());
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenFkUserIsNotSet(): void
    {
        $this->tester->haveUser();
        $commentTransfer = (new CommentBuilder([
            CommentTransfer::FK_USER => null,
        ]))->build();

        // Act
        $commentTransfers = $this->tester->getFacade()->expandCommentsWithUser([$commentTransfer]);

        // Assert
        $this->assertCount(1, $commentTransfers);
        $this->assertNull($commentTransfers[0]->getUser());
    }
}
