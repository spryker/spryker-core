<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerNote\Business;

use Codeception\Test\Unit;
use Spryker\Zed\CustomerNote\Business\CustomerNoteBusinessFactory;
use Spryker\Zed\CustomerNote\Business\CustomerNoteFacade;
use Spryker\Zed\CustomerNote\CustomerNoteDependencyProvider;
use Spryker\Zed\CustomerNote\Dependency\Facade\CustomerNoteToUserFacadeInterface;
use Spryker\Zed\Kernel\Container;

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
    public const NOTES_COUNT = 10;

    /**
     * @var \SprykerTest\Zed\CustomerNote\CustomerNoteBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\CustomerNote\Business\CustomerNoteFacadeInterface
     */
    protected $customerNoteFacade;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\UserTransfer
     */
    protected $userTransfer;

    /**
     * @var \Spryker\Zed\Kernel\Container
     */
    protected $businessLayerDependencies;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->customerNoteFacade = new CustomerNoteFacade();
        $this->customerTransfer = $this->getCustomer();
        $this->userTransfer = $this->getUser();
    }

    /**
     * @return \Spryker\Zed\CustomerNote\Business\CustomerNoteBusinessFactory
     */
    protected function getBusinessFactory()
    {
        $customerNoteBusinessFactory = new CustomerNoteBusinessFactory();
        $customerNoteBusinessFactory->setContainer($this->getContainer());

        return $customerNoteBusinessFactory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer()
    {
        $dependencyProvider = new CustomerNoteDependencyProvider();
        $this->businessLayerDependencies = new Container();

        $dependencyProvider->provideBusinessLayerDependencies($this->businessLayerDependencies);

        $this->businessLayerDependencies[CustomerNoteDependencyProvider::FACADE_USER] =
            $this->getMockBuilder(CustomerNoteToUserFacadeInterface::class)->getMock()
            ->method('getCurrentUser')
            ->willReturn($this->userTransfer);

        return $this->businessLayerDependencies;
    }

    /**
     * @return void
     */
    public function testAddNoteReturnsNotEmptyValueOnSuccess()
    {
        $note = $this->customerNoteFacade->addNote($this->tester->getCustomerNoteTransfer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer()
        ));

        $this->assertTrue((bool)$note->getIdCustomerNote());
    }

    /**
     * @return void
     */
    public function testAddNoteFromCurrentUserReturnsNotEmptyValueOnSuccess()
    {
        $note = $this->customerNoteFacade->addNote($this->tester->getCustomerNoteTransfer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer()
        ));

        $this->assertTrue((bool)$note->getIdCustomerNote());
    }

    /**
     * @return void
     */
    public function testGetNotesReturnsProperAmountOfNotes()
    {
        $this->createCustomerNotesWithFkUserAndFkCustomer(
            $this->userTransfer->getIdUser(),
            $this->customerTransfer->getIdCustomer(),
            static::NOTES_COUNT
        );
        $customerNoteCollectionTransfer = $this->customerNoteFacade->getNotes($this->customerTransfer->getIdCustomer());

        $this->assertSame(static::NOTES_COUNT, $customerNoteCollectionTransfer->getNotes()->count());
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getCustomer()
    {
        return $this->tester->haveCustomer();
    }

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getUser()
    {
        return $this->tester->haveUser();
    }

    /**
     * @param int $fkUser
     * @param int $fkCustomer
     * @param int $number
     *
     * @return void
     */
    protected function createCustomerNotesWithFkUserAndFkCustomer(int $fkUser, int $fkCustomer, int $number)
    {
        for ($i = 0; $i < $number; $i++) {
            $this->customerNoteFacade->addNote(
                $this->tester->getCustomerNoteTransfer($fkUser, $fkCustomer)
            );
        }
    }
}
