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
        $entityManager = new AbstractEntityManagerMock();

        $testTransfer = (new SpyTestEntityTransfer())
            ->setTest(rand(1, 100));

        $result = $entityManager->save($testTransfer);

        $testTransfer = (new SpyTestEntityTransfer())
            ->setIdTest($result->getIdTest())
            ->setTest(0);

        $newResult = $entityManager->save($testTransfer);

        $testEntity = SpyTestQuery::create()->findOneByIdTest($newResult->getIdTest());
        $this->assertEquals(0, $testEntity->getTest());
    }
}
