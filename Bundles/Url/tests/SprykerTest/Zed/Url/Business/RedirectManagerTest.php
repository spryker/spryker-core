<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Url\Business;

use Codeception\Test\Unit;
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
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Url
 * @group Business
 * @group RedirectManagerTest
 * Add your own group annotations below this line
 */
class RedirectManagerTest extends Unit
{
    /**
     * @return void
     */
    public function testDeleteUrlRedirectMustThrowExceptionIfUrlRedirectNotFound(): void
    {
        $this->expectException(MissingRedirectException::class);

        $queryContainer = new UrlQueryContainer();
        $queryContainer->setFactory(new UrlPersistenceFactory());

        $redirectedManager = new RedirectManager(
            $queryContainer,
            $this->getMockBuilder(UrlManagerInterface::class)->getMock(),
            $this->getMockBuilder(UrlToTouchInterface::class)->getMock(),
            $this->getMockBuilder(ConnectionInterface::class)->getMock(),
        );

        $redirectedManager->deleteUrlRedirect(new RedirectTransfer());
    }

    /**
     * @return void
     */
    public function testDeleteUrlRedirect(): void
    {
        $entityMock = $this->getMockBuilder(SpyUrlRedirect::class)->onlyMethods(['delete'])->getMock();
        $entityMock->expects($this->once())->method('delete');

        $redirectedManager = $this->getMockBuilder(RedirectManager::class)->onlyMethods(['getRedirectById', 'touchDeleted'])->disableOriginalConstructor()->getMock();
        $redirectedManager->method('getRedirectById')->willReturn($entityMock);
        $redirectedManager->expects($this->once())->method('touchDeleted');

        $redirectedManager->deleteUrlRedirect(new RedirectTransfer());
    }
}
