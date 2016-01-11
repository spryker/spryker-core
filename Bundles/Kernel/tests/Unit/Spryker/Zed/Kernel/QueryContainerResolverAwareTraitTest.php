<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Kernel\QueryContainerResolverAwareTrait;

/**
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group QueryContainerResolverAwareTrait
 */
class QueryContainerResolverAwareTraitTest extends \PHPUnit_Framework_TestCase
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
     * @return \PHPUnit_Framework_MockObject_MockObject|QueryContainerResolverAwareTrait
     */
    private function getQueryContainerResolverAwareTraitMock()
    {
        return $this->getMockForTrait(QueryContainerResolverAwareTrait::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|AbstractQueryContainer
     */
    private function getAbstractQueryContainerMock()
    {
        return $this->getMockForAbstractClass(AbstractQueryContainer::class);
    }

}
