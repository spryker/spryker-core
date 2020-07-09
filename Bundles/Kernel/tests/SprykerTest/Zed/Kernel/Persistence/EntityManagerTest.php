<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Persistence;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\SpyTestEntityTransfer;
use Orm\Zed\Kernel\Persistence\SpyTestQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group Persistence
 * @group EntityManagerTest
 * Add your own group annotations below this line
 */
class EntityManagerTest extends Test
{
    /**
     * @return void
     */
    public function testTestAbstractEntityManager(): void
    {
        // Arrange
        $entityManager = new AbstractEntityManagerMock();
        $testEntityTransfer = (new SpyTestEntityTransfer())->setTest(rand(1, 100));

        // Act
        /**
         * @var \Generated\Shared\Transfer\SpyTestEntityTransfer $result
         */
        $testEntityTransfer = $entityManager->save($testEntityTransfer);
        $testEntityTransfer->setTest(0);

        $testEntityTransfer = $entityManager->save($testEntityTransfer);
        $testEntityTransfer = SpyTestQuery::create()->findOneByIdTest($testEntityTransfer->getIdTest());

        // Assert
        $this->assertSame(0, $testEntityTransfer->getTest());
    }
}
