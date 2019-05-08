<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsPageSearch\Business;

use Spryker\Zed\CmsPageSearch\Business\CmsPageSearchBusinessFactory;
use Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToSearchInterface;

class CmsPageSearchBusinessFactoryMock extends CmsPageSearchBusinessFactory
{
    /**
     * @var \Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToSearchInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $searchFacadeMock;

    /**
     * @param \Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToSearchInterface|\PHPUnit\Framework\MockObject\MockObject $searchFacadeMock
     */
    public function __construct(CmsPageSearchToSearchInterface $searchFacadeMock)
    {
        $this->searchFacadeMock = $searchFacadeMock;
    }

    /**
     * @return \Spryker\Zed\CmsPageSearch\Dependency\Facade\CmsPageSearchToSearchInterface
     */
    public function getSearchFacade()
    {
        return $this->searchFacadeMock;
    }
}
