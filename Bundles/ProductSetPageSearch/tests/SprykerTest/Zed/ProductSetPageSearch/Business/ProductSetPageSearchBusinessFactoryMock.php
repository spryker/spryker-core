<?php
/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductSetPageSearch\Business;

use Spryker\Zed\ProductSetPageSearch\Business\ProductSetPageSearchBusinessFactory;
use Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToSearchInterface;

class ProductSetPageSearchBusinessFactoryMock extends ProductSetPageSearchBusinessFactory
{
    /**
     * @var \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToSearchInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $searchFacadeMock;

    /**
     * @param \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToSearchInterface|\PHPUnit_Framework_MockObject_MockObject $searchFacadeMock
     */
    public function __construct(ProductSetPageSearchToSearchInterface $searchFacadeMock)
    {
        $this->searchFacadeMock = $searchFacadeMock;
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToSearchInterface
     */
    public function getSearchFacade()
    {
        return $this->searchFacadeMock;
    }
}
