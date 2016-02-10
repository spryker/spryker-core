<?php

/**
 * (c) Spryker Systems GmbH copyright protected
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
use Spryker\Zed\Url\Persistence\UrlQueryContainerInterface;

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
        $this->setExpectedException(MissingRedirectException::class);

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
        $entityMock = $this->getMock(SpyUrlRedirect::class, ['delete']);
        $entityMock->expects($this->once())->method('delete');

        $redirectedManager = $this->getMock(RedirectManager::class, ['getRedirectById', 'touchDeleted'], [], '', false);
        $redirectedManager->method('getRedirectById')->willReturn($entityMock);
        $redirectedManager->expects($this->once())->method('touchDeleted');

        $redirectedManager->deleteUrlRedirect(new RedirectTransfer());
    }
}
