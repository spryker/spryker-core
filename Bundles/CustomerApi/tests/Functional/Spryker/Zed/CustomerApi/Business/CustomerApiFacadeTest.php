<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\CustomerApi\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
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
     * @return void
     */
    public function testGet()
    {
        $customerApiFacade = new CustomerApiFacade();

        $apiFilterTransfer = new ApiFilterTransfer();
        $idCustomer = 1;

        $resultTransfer = $customerApiFacade->getCustomer($idCustomer, $apiFilterTransfer);

        $this->assertInstanceOf(ApiItemTransfer::class, $resultTransfer);
    }

    /**
     * @return void
     */
    public function testFind()
    {
        $productApiFacade = new CustomerApiFacade();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiFilterTransfer = new ApiFilterTransfer();
        $apiRequestTransfer->setFilter($apiFilterTransfer);

        $resultTransfer = $productApiFacade->findCustomers($apiRequestTransfer);

        $this->assertInstanceOf(ApiCollectionTransfer::class, $resultTransfer);
    }

}
