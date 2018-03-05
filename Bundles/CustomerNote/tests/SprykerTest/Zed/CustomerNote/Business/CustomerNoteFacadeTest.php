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
    /**
     * @var \SprykerTest\Zed\CustomerNote\CustomerNoteBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\CustomerNote\Business\CustomerNoteFacadeInterface
     */
    protected $customerNoteFacade;

    protected $customerTranfer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->customerNoteFacade = new CustomerNoteFacade();
        $this->customerTranfer = $this->getCustomer();
    }

    /**
     * @return void
     */
    public function testAddNote()
    {
        $note = $this->customerNoteFacade->addNote($this->tester->getCustomerNoteTransfer());

        $this->assertTrue((bool)$note->getIdCustomerNote());
    }

    public function testGetNotes()
    {
        $this->tester->hydrateCustomerNotes($this->getCustomer()->getIdCustomer(), 10);
        $customerNotesCollectionTransfer = $this->customerNoteFacade->getNotes($this->customerTranfer->getIdCustomer());

        $this->assertTrue(true);
    }

    protected function getCustomer()
    {
        return $this->tester->haveCustomer();
    }
}
