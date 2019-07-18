<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductPageSearch\Business;

use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactory;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface;

class ProductPageSearchBusinessFactoryMock extends ProductPageSearchBusinessFactory
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $searchFacadeMock;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface|\PHPUnit\Framework\MockObject\MockObject $searchFacadeMock
     */
    public function __construct(ProductPageSearchToSearchInterface $searchFacadeMock)
    {
        $this->searchFacadeMock = $searchFacadeMock;
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchInterface
     */
    public function getSearchFacade()
    {
        return $this->searchFacadeMock;
    }
}
