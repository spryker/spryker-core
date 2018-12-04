<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Customer;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Customer
 * @group CustomerClientTest
 * Add your own group annotations below this line
 */
class CustomerClientTest extends Unit
{
    /**
     * @var \SprykerTest\Client\Customer\CustomerClientTester
     */
    protected $tester;

    /**
     * @var \Spryker\Client\Customer\CustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $sessionClient;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->customerClient = $this->tester->getLocator()->customer()->client();
        $this->sessionClient = $this->tester->getLocator()->session()->client();
    }

    /**
     * @return void
     */
    public function testSessionSuccessfullyMigrated(): void
    {
        $this->sessionClient->start();
        $oldSessionCreatedTime = $this->sessionClient->getMetadataBag()->getCreated();
        sleep(1);
        $result = $this->customerClient->extendSessionLifetime();
        $newSessionCreatedTime = $this->sessionClient->getMetadataBag()->getCreated();

        $this->assertTrue($result);
        $this->assertGreaterThan($oldSessionCreatedTime, $newSessionCreatedTime);
    }
}
