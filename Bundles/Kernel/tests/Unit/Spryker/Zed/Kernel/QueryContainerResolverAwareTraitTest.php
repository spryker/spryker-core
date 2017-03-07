<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Kernel\QueryContainerResolverAwareTrait;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group QueryContainerResolverAwareTraitTest
 */
class QueryContainerResolverAwareTraitTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testSetQueryContainerMustReturnFluentInterface()
    {
        $queryContainerResolverAwareTraitMock = $this->getQueryContainerResolverAwareTraitMock();
        $returned = $queryContainerResolverAwareTraitMock->setQueryContainer(
            $this->getAbstractQueryContainerMock()
        );

        $this->assertSame($queryContainerResolverAwareTraitMock, $returned);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Kernel\QueryContainerResolverAwareTrait
     */
    private function getQueryContainerResolverAwareTraitMock()
    {
        return $this->getMockForTrait(QueryContainerResolverAwareTrait::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    private function getAbstractQueryContainerMock()
    {
        return $this->getMockForAbstractClass(AbstractQueryContainer::class);
    }

}
