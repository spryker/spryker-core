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
use Orm\Zed\Customer\Persistence\SpyCustomer;
use RuntimeException;
use Spryker\Zed\Api\Business\Exception\EntityNotFoundException;
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
    public function setUp()
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
    public function testGet()
    {
        $customerApiFacade = new CustomerApiFacade();

        $idCustomer = $this->idCustomer;

        $resultTransfer = $customerApiFacade->getCustomer($idCustomer);

        $this->assertInstanceOf(ApiItemTransfer::class, $resultTransfer);

        $id = $resultTransfer->getId();
        $this->assertNotEmpty($id);

        $data = $resultTransfer->getData();
        $this->assertNotEmpty($data['customer_reference']);
    }

    /**
     * @return void
     */
    public function testGetInvalid()
    {
        $customerApiFacade = new CustomerApiFacade();

        $idCustomer = 999;

        $this->expectException(EntityNotFoundException::class);

        $customerApiFacade->getCustomer($idCustomer);
    }

    /**
     * @return void
     */
    public function testFind()
    {
        $customerApiFacade = new CustomerApiFacade();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiFilterTransfer = new ApiFilterTransfer();
        $apiRequestTransfer->setFilter($apiFilterTransfer);

        $resultTransfer = $customerApiFacade->findCustomers($apiRequestTransfer);

        $this->assertInstanceOf(ApiCollectionTransfer::class, $resultTransfer);

        $data = $resultTransfer->getData();
        $this->assertNotEmpty($data[0]['customer_reference']);
    }

    /**
     * @return void
     */
    public function testAdd()
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
        $this->assertNotEmpty($data['id_customer']);
    }

    /**
     * @return void
     */
    public function testEdit()
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
        $this->assertNotEmpty($data['id_customer']);
    }

    /**
     * @return void
     */
    public function testDelete()
    {
        $customerApiFacade = new CustomerApiFacade();

        $idCustomer = $this->idCustomer;

        $result = $customerApiFacade->removeCustomer($idCustomer);

        $this->assertSame($this->idCustomer, $result->getId());
    }

    /**
     * @return void
     */
    public function testDeleteInvalid()
    {
        $customerApiFacade = new CustomerApiFacade();

        $idCustomer = 999;

        $result = $customerApiFacade->removeCustomer($idCustomer);

        $this->assertNull($result->getId());
    }
}
