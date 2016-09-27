<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\ProductImage\Business\Model;

use Codeception\TestCase\Test;
use Spryker\Zed\ProductImage\Business\ProductImageFacade;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group ProductImage
 * @group Business
 * @group Model
 * @group ProductImageFacadeTest
 */
class ProductImageFacadeTest extends Test
{

    /**
     * @var \Spryker\Zed\ProductImage\Business\Model\ReaderInterface
     */
    protected $facade;

    protected function setUp()
    {
        $this->facade = new ProductImageFacade();
    }

}
