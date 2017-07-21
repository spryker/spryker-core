<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Storage\Business;

use Codeception\TestCase\Test;
use Spryker\Zed\Storage\Business\StorageFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Storage
 * @group Business
 * @group Facade
 * @group StorageFacadeTest
 * Add your own group annotations below this line
 */
class StorageFacadeTest extends Test
{

    /**
     * @var \SprykerTest\Zed\Storage\BusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Storage\Business\StorageFacade
     */
    protected $storageFacade;

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
