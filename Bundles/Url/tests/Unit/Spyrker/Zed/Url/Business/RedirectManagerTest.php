<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Url\Business;

use Generated\Shared\Transfer\RedirectTransfer;
use Orm\Zed\Url\Persistence\SpyUrlRedirect;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Url\Business\Exception\MissingRedirectException;
use Spryker\Zed\Url\Business\RedirectManager;
use Spryker\Zed\Url\Business\UrlManagerInterface;
use Spryker\Zed\Url\Dependency\UrlToTouchInterface;
use Spryker\Zed\Url\Persistence\UrlPersistenceFactory;
use Spryker\Zed\Url\Persistence\UrlQueryContainer;

/**
 * @group Spryker
 * @group Zed
 * @group Url
 * @group Business
 */
class RedirectManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testDeleteUrlRedirectMustThrowExceptionIfUrlRedirectNotFound()
    {
        $this->expectException(MissingRedirectException::class);

        $queryContainer = new UrlQueryContainer();
        $queryContainer->setFactory(new UrlPersistenceFactory());

        $redirectedManager = new RedirectManager(
            $queryContainer,
            $this->getMock(UrlManagerInterface::class),
            $this->getMock(UrlToTouchInterface::class),
            $this->getMock(ConnectionInterface::class)
        );

        $redirectedManager->deleteUrlRedirect(new RedirectTransfer());
    }

    /**
     * @return void
     */
    public function testDeleteUrlRedirect()
    {
        $entityMock = $this->getMockBuilder(SpyUrlRedirect::class)->setMethods(['delete'])->getMock();
        $entityMock->expects($this->once())->method('delete');

        $redirectedManager = $this->getMockBuilder(RedirectManager::class)->setMethods(['getRedirectById', 'touchDeleted'])->disableOriginalConstructor()->getMock();
        $redirectedManager->method('getRedirectById')->willReturn($entityMock);
        $redirectedManager->expects($this->once())->method('touchDeleted');

        $redirectedManager->deleteUrlRedirect(new RedirectTransfer());
    }

}
