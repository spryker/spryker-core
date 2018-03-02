<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerNote\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Spryker\Zed\CustomerNote\Business\CustomerNoteFacade;
use Spryker\Zed\CustomerNote\Persistence\CustomerNoteEntityManager;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CustomerNote
 * @group Business
 * @group Facade
 * @group CustomerNoteFacadeTest
 * Add your own group annotations below this line
 */
class CustomerNoteFacadeTest extends Unit
{
    const TESTER_EMAIL = 'tester@spryker.com';
    const TESTER_PASSWORD = 'tester';
    const TEST_NOTE_AUTHOR = 'Admin';
    const TEST_NOTE_MESSAGE = 'test';

    /**
     * @var \SprykerTest\Zed\CustomerNote\CustomerNoteBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\CustomerNote\Business\CustomerNoteFacadeInterface
     */
    protected $customerNoteFacade;

    /**
     * @var \Spryker\Zed\Kernel\Container
     */
    protected $businessLayerDependencies;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->customerNoteFacade = new CustomerNoteFacade();
        $this->customer = $this->createCustomerTransfer();
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerBusinessFactory
     */
    protected function getEntityManager()
    {
        $customerNoteEntityManger = new CustomerNoteEntityManager();

        return $customerNoteEntityManger;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer()
    {
        $customerTransfer = $this->tester->haveCustomer();

        return $customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer
     */
    protected function createNoteTransfer()
    {
        $noteTransfer = new SpyCustomerNoteEntityTransfer();
        $commentTransfer->setMessage(self::TEST_NOTE_MESSAGE);
        $commentTransfer->setUsername(
            self::TEST_NOTE_AUTHOR
        );
        $noteTransfer->setFkCustomer($this->customer->getIdCustomer());

        return $noteTransfer;
    }

    /**
     * @return void
     */
    public function testAddNote()
    {
        $note = $this->customerNoteFacade->addNote($this->createNoteTransfer());

        $this->assertTrue((bool)$note->getIdCustomerNote());
    }
}
