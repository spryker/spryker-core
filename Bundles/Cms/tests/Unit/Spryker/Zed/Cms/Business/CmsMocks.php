<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Cms\Business;

use Orm\Zed\Cms\Persistence\SpyCmsPage;
use PHPUnit_Framework_TestCase;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

abstract class CmsMocks extends PHPUnit_Framework_TestCase
{

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface|null $propelConnectionMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected function createCmsQueryContainerMock(ConnectionInterface $propelConnectionMock = null)
    {
        $cmsQueryContainerMock = $this->getMockBuilder(CmsQueryContainerInterface::class)
            ->getMock();

        if ($propelConnectionMock === null) {
            $propelConnectionMock = $this->createPropelConnectionMock();
        }

        $cmsQueryContainerMock->method('getConnection')
            ->willReturn($propelConnectionMock);

        return $cmsQueryContainerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Propel\Runtime\Connection\ConnectionInterface
     */
    protected function createPropelConnectionMock()
    {
        return $this->getMockBuilder(ConnectionInterface::class)->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface
     */
    protected function createTouchFacadeMock()
    {
        return $this->getMockBuilder(CmsToTouchInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    protected function createCmsPageEntityMock()
    {
        return $this->getMockBuilder(SpyCmsPage::class)
            ->setMethods(['save'])
            ->getMock();
    }

}
