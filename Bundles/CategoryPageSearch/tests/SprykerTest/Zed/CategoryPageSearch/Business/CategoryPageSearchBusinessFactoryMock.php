<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryPageSearch\Business;

use Spryker\Zed\CategoryPageSearch\Business\CategoryPageSearchBusinessFactory;
use Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToSearchInterface;

class CategoryPageSearchBusinessFactoryMock extends CategoryPageSearchBusinessFactory
{
    /**
     * @var \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToSearchInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $searchFacadeMock;

    /**
     * @param \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToSearchInterface|\PHPUnit_Framework_MockObject_MockObject $searchFacadeMock
     */
    public function __construct(CategoryPageSearchToSearchInterface $searchFacadeMock)
    {
        $this->searchFacadeMock = $searchFacadeMock;
    }

    /**
     * @return \Spryker\Zed\CategoryPageSearch\Dependency\Facade\CategoryPageSearchToSearchInterface
     */
    public function getSearchFacade()
    {
        return $this->searchFacadeMock;
    }
}
