<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Storage\Business;

use Codeception\TestCase\Test;
use Spryker\Zed\Storage\Business\StorageFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Storage
 * @group Business
 * @group StorageFacadeTest
 */
class StorageFacadeTest extends Test
{

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->skipIfCircleCi();
        $this->storageFacade = new StorageFacade();
    }

    /**
     * @return void
     */
    protected function skipIfCircleCi()
    {
        if (getenv('CIRCLECI') || getenv('TRAVIS')) {
            $this->markTestSkipped('Circle ci not set up properly');
        }
    }

    /**
     * @return void
     */
    public function testGetTotalCount()
    {
        $count = $this->storageFacade->getTotalCount();

        $this->assertTrue($count > 0);
    }

}
