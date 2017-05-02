<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductApi\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ApiCollectionTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\ProductApi\Business\ProductApiFacade;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group ProductApi
 * @group Business
 * @group ProductApiFacadeTest
 */
class ProductApiFacadeTest extends Test
{

    /**
     * @return void
     */
    public function testGet()
    {
        $productApiFacade = new ProductApiFacade();

        $apiFilterTransfer = new ApiFilterTransfer();
        $idProduct = 1;

        $resultTransfer = $productApiFacade->getProduct($idProduct, $apiFilterTransfer);

        $this->assertInstanceOf(ApiItemTransfer::class, $resultTransfer);
    }

    /**
     * @return void
     */
    public function testFind()
    {
        $productApiFacade = new ProductApiFacade();

        $apiRequestTransfer = new ApiRequestTransfer();
        $apiFilterTransfer = new ApiFilterTransfer();
        $apiRequestTransfer->setFilter($apiFilterTransfer);

        $resultTransfer = $productApiFacade->findProducts($apiRequestTransfer);

        $this->assertInstanceOf(ApiCollectionTransfer::class, $resultTransfer);
    }

}
