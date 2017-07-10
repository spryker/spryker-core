<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\ProductAttribute\Business\Model;

use Codeception\TestCase\Test;
use Spryker\Zed\ProductManagement\Business\ProductManagementFacade;
use Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group ProductAttribute
 * @group Business
 * @group Model
 * @group ProductAttributeReaderTest
 */
class ProductAttributeReaderTest extends Test
{

    /**
     * @var \Spryker\Zed\ProductManagement\Business\ProductManagementFacade
     */
    protected $productManagementFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainer
     */
    protected $productManagementQueryContainer;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->productManagementFacade = new ProductManagementFacade();
        $this->productManagementQueryContainer = new ProductManagementQueryContainer();
    }

}
