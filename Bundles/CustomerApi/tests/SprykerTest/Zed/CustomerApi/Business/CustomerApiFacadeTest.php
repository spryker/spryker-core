<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerApi\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\CustomerApiTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use RuntimeException;
use Spryker\Zed\CustomerApi\Business\CustomerApiFacade;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CustomerApi
 * @group Business
 * @group Facade
 * @group CustomerApiFacadeTest
 * Add your own group annotations below this line
 */
class CustomerApiFacadeTest extends Unit
{
    /**
     * @var int
     */
    protected $idCustomer;

    /**
     * @throws \RuntimeException
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $customerEntity = new SpyCustomer();
        $customerEntity->setEmail('foo' . time() . '@bar.de');
        $customerEntity->setCustomerReference('foobar' . time());

        $customerEntity->save();
        $this->idCustomer = $customerEntity->getIdCustomer();
        if ($this->idCustomer === null) {
            throw new RuntimeException('Adding test data failed');
        }
    }

    /**
     * @return void
     */
    public function testGet(): void
    {
        $customerApiFacade = new CustomerApiFacade();

        $idCustomer = $this->idCustomer;

        $resultTransfer = $customerApiFacade->getCustomer($idCustomer);

        $this->assertInstanceOf(ApiItemTransfer::class, $resultTransfer);

        $id = $resultTransfer->getId();
        $this->assertNotEmpty($id);

        $data = $resultTransfer->getData();
        $this->assertNotEmpty($data[CustomerApiTransfer::CUSTOMER_REFERENCE]);
    }

    /**
     * @return void
     */
    public function testGetInvalid(): void
    {
        // Arrange
        $customerApiFacade = new CustomerApiFacade();
        $idCustomer = 999;

        // Act
        $apiItemTransfer = $customerApiFacade->getCustomer($idCustomer);

        // Assert
        $this->assertCount(1, $apiItemTransfer->getValidationErrors());

        $messages = $apiItemTransfer->getValidationErrors()->offsetGet(0)->getMessages();
        $this->assertCount(1, $messages);
        $this->assertSame(sprintf('Customer not found: %s', $idCustomer), $messages[0]);
    }

    /**
     * @return void
     */
    public function testFind(): void
    {
        $customerApiFacade = new CustomerApiFacade();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiFilterTransfer = new ApiFilterTransfer();
        $apiRequestTransfer->setFilter($apiFilterTransfer);

        $resultTransfer = $customerApiFacade->findCustomers($apiRequestTransfer);

        $this->assertInstanceOf(ApiCollectionTransfer::class, $resultTransfer);

        $data = $resultTransfer->getData();
        $this->assertNotEmpty($data[0][CustomerApiTransfer::CUSTOMER_REFERENCE]);
    }

    /**
     * @return void
     */
    public function testAdd(): void
    {
        $customerApiFacade = new CustomerApiFacade();

        $apiDataTransfer = new ApiDataTransfer();
        $apiDataTransfer->setData([
            'email' => 'foo' . time() . '@example.de',
            'customer_reference' => 'foobar' . time() . 'example',
        ]);

        $resultTransfer = $customerApiFacade->addCustomer($apiDataTransfer);

        $this->assertInstanceOf(ApiItemTransfer::class, $resultTransfer);

        $id = $resultTransfer->getId();
        $this->assertNotEmpty($id);

        $data = $resultTransfer->getData();
        $this->assertNotEmpty($data[CustomerApiTransfer::ID_CUSTOMER]);
    }

    /**
     * @return void
     */
    public function testEdit(): void
    {
        $customerApiFacade = new CustomerApiFacade();

        $apiDataTransfer = new ApiDataTransfer();
        $apiDataTransfer->setData([
            'email' => 'foo' . time() . '@example.de',
            'customer_reference' => 'foobar' . time() . 'example',
        ]);

        $idCustomer = $this->idCustomer;
        $resultTransfer = $customerApiFacade->updateCustomer($idCustomer, $apiDataTransfer);

        $this->assertInstanceOf(ApiItemTransfer::class, $resultTransfer);

        $id = $resultTransfer->getId();
        $this->assertNotEmpty($id);

        $data = $resultTransfer->getData();
        $this->assertNotEmpty($data[CustomerApiTransfer::ID_CUSTOMER]);
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        $customerApiFacade = new CustomerApiFacade();

        $idCustomer = $this->idCustomer;

        $result = $customerApiFacade->removeCustomer($idCustomer);

        $this->assertSame((string)$this->idCustomer, $result->getId());
    }

    /**
     * @return void
     */
    public function testDeleteInvalid(): void
    {
        $customerApiFacade = new CustomerApiFacade();

        $idCustomer = 999;

        $result = $customerApiFacade->removeCustomer($idCustomer);

        $this->assertNull($result->getId());
    }
}
