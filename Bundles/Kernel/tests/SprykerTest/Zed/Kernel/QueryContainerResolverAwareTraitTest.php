<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Kernel\QueryContainerResolverAwareTrait;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group QueryContainerResolverAwareTraitTest
 * Add your own group annotations below this line
 */
class QueryContainerResolverAwareTraitTest extends Unit
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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\QueryContainerResolverAwareTrait
     */
    private function getQueryContainerResolverAwareTraitMock()
    {
        return $this->getMockForTrait(QueryContainerResolverAwareTrait::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    private function getAbstractQueryContainerMock()
    {
        return $this->getMockForAbstractClass(AbstractQueryContainer::class);
    }
}
