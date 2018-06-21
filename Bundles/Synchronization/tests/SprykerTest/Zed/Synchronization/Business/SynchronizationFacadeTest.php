<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Synchronization\Business;

use Codeception\Test\Unit;
use Elastica\Exception\NotFoundException;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Synchronization\Business\Message\QueueMessageCreatorInterface;
use Spryker\Zed\Synchronization\Business\SynchronizationBusinessFactory;
use Spryker\Zed\Synchronization\Business\SynchronizationFacade;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchClientInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToStorageClientInterface;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface;
use Spryker\Zed\Synchronization\SynchronizationDependencyProvider;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Synchronization
 * @group Business
 * @group Facade
 * @group SynchronizationFacadeTest
 * Add your own group annotations below this line
 */
class SynchronizationFacadeTest extends Unit
{
    /**
     * @var \Spryker\Zed\Synchronization\Business\SynchronizationFacadeInterface
     */
    protected $synchronizationFacade;

    /**
     * @return void
     */
    public function testSynchronizationWritesDataToStorage()
    {
        $container = new Container();
        $container[SynchronizationDependencyProvider::CLIENT_STORAGE] = function (Container $container) {
            $storageMock = $this->createStorageClientBridge();
            $storageMock->expects($this->once())->method('set')->will(
                $this->returnCallback(
                    function ($key, $value) {
                        $this->assertEquals($key, 'testKey');
                        $this->assertEquals($value, ['data' => 'testValue']);
                    }
                )
            );

            return $storageMock;
        };

        $container[SynchronizationDependencyProvider::SERVICE_UTIL_ENCODING] = function (Container $container) {
            $utilEncodingMock = $this->createUtilEncodingServiceBridge();
            $utilEncodingMock->expects($this->once())->method('encodeJson')->willReturnArgument(0);

            return $utilEncodingMock;
        };

        $this->prepareFacade($container);
        $this->synchronizationFacade->storageWrite([
            'key' => 'testKey',
            'value' => ['data' => 'testValue'],
        ], 'test');
    }

    /**
     * @return void
     */
    public function testSynchronizationDeletesDataToStorage()
    {
        $container = new Container();
        $container[SynchronizationDependencyProvider::CLIENT_STORAGE] = function (Container $container) {
            $storageMock = $this->createStorageClientBridge();
            $storageMock->expects($this->once())->method('delete')->will(
                $this->returnCallback(
                    function ($key) {
                        $this->assertEquals($key, 'testKey');
                    }
                )
            );

            return $storageMock;
        };

        $container[SynchronizationDependencyProvider::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return $this->createUtilEncodingServiceBridge();
        };

        $this->prepareFacade($container);
        $this->synchronizationFacade->storageDelete([
            'key' => 'testKey',
            'value' => ['data' => 'testValue'],
        ], 'test');
    }

    /**
     * @throws \Elastica\Exception\NotFoundException
     *
     * @return void
     */
    public function testSynchronizationWritesDataToSearch()
    {
        $container = new Container();
        $container[SynchronizationDependencyProvider::CLIENT_SEARCH] = function (Container $container) {
            $searchMock = $this->createSearchClientBridge();
            $searchMock->expects($this->once())->method('write')->will(
                $this->returnCallback(
                    function ($data) {
                        $this->assertEquals(key($data), 'testKey');
                        $this->assertEquals(current($data), ['data' => 'testValue']);
                    }
                )
            );
            $searchMock->expects($this->once())->method('read')->will($this->returnCallback(
                function ($key) {
                    throw new NotFoundException();
                }
            ));

            return $searchMock;
        };

        $container[SynchronizationDependencyProvider::SERVICE_UTIL_ENCODING] = function (Container $container) {
            $utilEncodingMock = $this->createUtilEncodingServiceBridge();

            return $utilEncodingMock;
        };

        $this->prepareFacade($container);
        $this->synchronizationFacade->searchWrite([
            'key' => 'testKey',
            'value' => ['data' => 'testValue'],
        ], 'test');
    }

    /**
     * @throws \Elastica\Exception\NotFoundException
     *
     * @return void
     */
    public function testSynchronizationDeleteDataToSearch()
    {
        $container = new Container();
        $container[SynchronizationDependencyProvider::CLIENT_SEARCH] = function (Container $container) {
            $searchMock = $this->createSearchClientBridge();
            $searchMock->expects($this->once())->method('delete')->will(
                $this->returnCallback(
                    function ($data) {
                        $this->assertEquals(key($data), 'testKey');
                    }
                )
            );

            $searchMock->expects($this->once())->method('read')->will($this->returnCallback(
                function ($key) {
                    throw new NotFoundException();
                }
            ));

            return $searchMock;
        };

        $container[SynchronizationDependencyProvider::SERVICE_UTIL_ENCODING] = function (Container $container) {
            $utilEncodingMock = $this->createUtilEncodingServiceBridge();

            return $utilEncodingMock;
        };

        $this->prepareFacade($container);
        $this->synchronizationFacade->searchDelete([
            'key' => 'testKey',
            'value' => ['data' => 'testValue'],
        ], 'test');
    }

    /**
     * @return void
     */
    public function testExportSynchronizedData()
    {
        $container = new Container();
        $container[SynchronizationDependencyProvider::CLIENT_QUEUE] = function (Container $container) {
            return $this->createQueueClientBridge();
        };

        $container[SynchronizationDependencyProvider::PLUGINS_SYNCHRONIZATION_DATA] = function (Container $container) {
            return $this->createSynchronizationDataPlugins();
        };
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createQueueClientBridge()
    {
        return $this->getMockBuilder(SynchronizationToQueueClientInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'sendMessages',
            ])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createSynchronizationDataPlugins()
    {
        return [

        ];
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createStorageClientBridge()
    {
        return $this->getMockBuilder(SynchronizationToStorageClientInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'set',
                'get',
                'delete',
            ])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createSearchClientBridge()
    {
        return $this->getMockBuilder(SynchronizationToSearchClientInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'write',
                'read',
                'delete',
            ])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createUtilEncodingServiceBridge()
    {
        return $this->getMockBuilder(SynchronizationToUtilEncodingServiceInterface::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'encodeJson',
                'decodeJson',
            ])
            ->getMock();
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function prepareFacade($container)
    {
        $synchronizationBusinessFactory = new SynchronizationBusinessFactory();
        $synchronizationBusinessFactory->setContainer($container);

        $this->synchronizationFacade = new SynchronizationFacade();
        $this->synchronizationFacade->setFactory($synchronizationBusinessFactory);
    }
}
