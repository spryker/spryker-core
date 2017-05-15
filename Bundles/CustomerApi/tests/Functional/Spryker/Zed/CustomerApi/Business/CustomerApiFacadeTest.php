<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\CustomerApi\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Spryker\Zed\CustomerApi\Business\CustomerApiFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group CustomerApi
 * @group Business
 * @group CustomerApiFacadeTest
 */
class CustomerApiFacadeTest extends Test
{

    /**
     * @var int
     */
    protected $idCustomer;

    /**
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

        $data = $resultTransfer->getData();
        //$this->assertNotEmpty($resultTransfer->getData()['customer_reference']);
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

        $data = $resultTransfer->getData();
        //$this->assertNotEmpty($data['id_customer']);
    }

}
