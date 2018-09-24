<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Synchronization\Business;

use Codeception\Test\Unit;
use Elastica\Exception\NotFoundException;
use Generated\Shared\Transfer\QueueReceiveMessageTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Generated\Shared\Transfer\SynchronizationDataTransfer;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\AvailabilityStorage\Communication\Plugin\Synchronization\AvailabilitySynchronizationDataPlugin;
use Spryker\Zed\CategoryPageSearch\Communication\Plugin\Synchronization\CategoryPageSynchronizationDataPlugin;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Synchronization\CategoryNodeSynchronizationDataPlugin;
use Spryker\Zed\CategoryStorage\Communication\Plugin\Synchronization\CategoryTreeSynchronizationDataPlugin;
use Spryker\Zed\CmsBlockCategoryStorage\Communication\Plugin\Synchronization\CmsBlockCategorySynchronizationDataPlugin;
use Spryker\Zed\CmsBlockProductStorage\Communication\Plugin\Synchronization\CmsBlockProductSynchronizationDataPlugin;
use Spryker\Zed\CmsBlockStorage\Communication\Plugin\Synchronization\CmsBlockSynchronizationDataPlugin;
use Spryker\Zed\CmsPageSearch\Communication\Plugin\Synchronization\CmsPageSynchronizationDataPlugin;
use Spryker\Zed\CmsStorage\Communication\Plugin\Synchronization\CmsSynchronizationDataPlugin;
use Spryker\Zed\GlossaryStorage\Communication\Plugin\Synchronization\GlossarySynchronizationDataPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\NavigationStorage\Communication\Plugin\Synchronization\NavigationSynchronizationDataPlugin;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Synchronization\PriceProductAbstractSynchronizationDataPlugin;
use Spryker\Zed\PriceProductStorage\Communication\Plugin\Synchronization\PriceProductConcreteSynchronizationDataPlugin;
use Spryker\Zed\ProductCategoryFilterStorage\Communication\Plugin\Synchronization\ProductCategoryFilterSynchronizationDataPlugin;
use Spryker\Zed\ProductCategoryStorage\Communication\Plugin\Synchronization\ProductCategorySynchronizationDataPlugin;
use Spryker\Zed\ProductGroupStorage\Communication\Plugin\Synchronization\ProductGroupSynchronizationDataPlugin;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Synchronization\ProductAbstractImageSynchronizationDataPlugin;
use Spryker\Zed\ProductImageStorage\Communication\Plugin\Synchronization\ProductConcreteImageSynchronizationDataPlugin;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Synchronization\ProductAbstractLabelSynchronizationDataPlugin;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Synchronization\ProductLabelDictionarySynchronizationDataPlugin;
use Spryker\Zed\ProductMeasurementUnitStorage\Communication\Plugin\Synchronization\ProductConcreteMeasurementUnitSynchronizationDataPlugin;
use Spryker\Zed\ProductMeasurementUnitStorage\Communication\Plugin\Synchronization\ProductMeasurementUnitSynchronizationDataPlugin;
use Spryker\Zed\ProductOptionStorage\Communication\Plugin\Synchronization\ProductOptionSynchronizationDataPlugin;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\Synchronization\ProductPageSynchronizationDataPlugin;
use Spryker\Zed\ProductQuantityStorage\Communication\Plugin\Synchronization\ProductQuantitySynchronizationDataPlugin;
use Spryker\Zed\ProductRelationStorage\Communication\Plugin\Synchronization\ProductRelationSynchronizationDataPlugin;
use Spryker\Zed\ProductReviewSearch\Communication\Plugin\Synchronization\ProductReviewSynchronizationDataPlugin as ProductReviewSearchSynchronizationDataPlugin;
use Spryker\Zed\ProductReviewStorage\Communication\Plugin\Synchronization\ProductReviewSynchronizationDataPlugin;
use Spryker\Zed\ProductSearchConfigStorage\Communication\Plugin\Synchronization\ProductSearchConfigSynchronizationDataPlugin;
use Spryker\Zed\ProductSetPageSearch\Communication\Plugin\Synchronization\ProductSetSynchronizationDataPlugin as ProductSetSearchSynchronizationDataPlugin;
use Spryker\Zed\ProductSetStorage\Communication\Plugin\Synchronization\ProductSetSynchronizationDataPlugin;
use Spryker\Zed\ProductStorage\Communication\Plugin\Synchronization\ProductAbstractSynchronizationDataPlugin;
use Spryker\Zed\ProductStorage\Communication\Plugin\Synchronization\ProductConcreteSynchronizationDataPlugin;
use Spryker\Zed\Synchronization\Business\SynchronizationBusinessFactory;
use Spryker\Zed\Synchronization\Business\SynchronizationFacade;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToQueueClientInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchClientInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToStorageClientInterface;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceBridge;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceInterface;
use Spryker\Zed\Synchronization\SynchronizationDependencyProvider;
use Spryker\Zed\UrlStorage\Communication\Plugin\Synchronization\UrlRedirectSynchronizationDataPlugin;
use Spryker\Zed\UrlStorage\Communication\Plugin\Synchronization\UrlSynchronizationDataPlugin;

/**
 * Auto-generated group annotations
 *
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
    protected const PARAM_PROJECT = 'PROJECT';

    protected const PROJECT_SUITE = 'suite';

    /**
     * @var \Spryker\Zed\Synchronization\Business\SynchronizationFacadeInterface
     */
    protected $synchronizationFacade;

    /**
     * @return void
     */
    public function testProcessSearchMessages(): void
    {
        $queueMessageBody = [
            'write' => [
                'key' => 'test',
                'value' => [
                    'some' => 'data',
                ],
            ],
            'delete' => [
                'key' => 'test1',
                'value' => [
                    'some' => 'data',
                ],
            ],
        ];

        $queueMessage = new QueueSendMessageTransfer();
        $queueMessage->setBody(json_encode($queueMessageBody));

        $queueMessageTransfer = new QueueReceiveMessageTransfer();
        $queueMessageTransfer->setQueueName('test');
        $queueMessageTransfer->setQueueMessage($queueMessage);

        $container = new Container();

        $container[SynchronizationDependencyProvider::SERVICE_UTIL_ENCODING] = $this->createUtilEncodingServiceBridge();

        $searchClientBridgeMock = $this->createSearchClientBridge();
        $searchClientBridgeMock
            ->method('read')
            ->willReturn(new SynchronizationDataTransfer());

        $searchClientBridgeMock
            ->expects($this->once())
            ->method('writeBulk')
            ->with($this->callback(function (array $data) use ($queueMessageBody) {
                $searchDocumentTransfer = new SearchDocumentTransfer();
                $searchDocumentTransfer->setId($queueMessageBody['write']['key']);
                $searchDocumentTransfer->setData($queueMessageBody['write']['value']);

                $this->assertEquals($searchDocumentTransfer->toArray(), $data[0]->toArray());

                return true;
            }));

        $searchClientBridgeMock
            ->expects($this->once())
            ->method('deleteBulk')
            ->with($this->callback(function (array $data) use ($queueMessageBody) {
                $searchDocumentTransfer = new SearchDocumentTransfer();
                $searchDocumentTransfer->setId($queueMessageBody['delete']['key']);
                $searchDocumentTransfer->setData($queueMessageBody['delete']['value']);

                $this->assertEquals($searchDocumentTransfer->toArray(), $data[0]->toArray());

                return true;
            }));

        $container[SynchronizationDependencyProvider::CLIENT_SEARCH] = $searchClientBridgeMock;

        $this->prepareFacade($container);

        $this->synchronizationFacade->processSearchMessages([$queueMessageTransfer]);
    }

    /**
     * @return void
     */
    public function testProcessStorageMessages(): void
    {
        $queueMessageBody = [
            'write' => [
                'key' => 'test',
                'value' => [
                    'some' => 'data',
                ],
            ],
            'delete' => [
                'key' => 'test1',
                'value' => [
                    'some' => 'data',
                ],
            ],
        ];

        $queueMessage = new QueueSendMessageTransfer();
        $queueMessage->setBody(json_encode($queueMessageBody));

        $queueMessageTransfer = new QueueReceiveMessageTransfer();
        $queueMessageTransfer->setQueueName('test');
        $queueMessageTransfer->setQueueMessage($queueMessage);

        $container = new Container();

        $container[SynchronizationDependencyProvider::SERVICE_UTIL_ENCODING] = $this->createUtilEncodingServiceBridge();

        $storageClientMock = $this->createStorageClientBridge();
        $storageClientMock
            ->expects($this->once())
            ->method('setMulti')
            ->with($this->callback(function (array $data) use ($queueMessageBody) {
                $this->assertEquals([$queueMessageBody['write']['key'] => $queueMessageBody['write']['value']], $data);

                return true;
            }));

        $storageClientMock
            ->expects($this->once())
            ->method('deleteMulti')
            ->with($this->callback(function (array $data) use ($queueMessageBody) {
                $this->assertEquals([$queueMessageBody['delete']['key']], $data);

                return true;
            }));

        $container[SynchronizationDependencyProvider::CLIENT_STORAGE] = $storageClientMock;

        $this->prepareFacade($container);

        $this->synchronizationFacade->processStorageMessages([$queueMessageTransfer]);
    }

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
            $utilEncodingMock = $this->createUtilEncodingServiceBridgeMock();
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
            return $this->createUtilEncodingServiceBridgeMock();
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
            $utilEncodingMock = $this->createUtilEncodingServiceBridgeMock();

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
            $utilEncodingMock = $this->createUtilEncodingServiceBridgeMock();

            return $utilEncodingMock;
        };

        $this->prepareFacade($container);
        $this->synchronizationFacade->searchDelete([
            'key' => 'testKey',
            'value' => ['data' => 'testValue'],
        ], 'test');
    }

    /**
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    public function testExecuteResolvedPluginsBySources()
    {
        if (!$this->isSuiteProject()) {
            throw new SkippedTestError('Warning: not in suite environment');
        }

        $container = new Container();
        $container[SynchronizationDependencyProvider::CLIENT_QUEUE] = function (Container $container) {
            $queueMock = $this->createQueueClientBridge();
            $synchronizationPlugins = $this->createSynchronizationDataPlugins();

            if (count($synchronizationPlugins) && $this->isSuiteProject()) {
                $queueMock->expects($this->atLeastOnce())->method('sendMessages');
                return $queueMock;
            }

            $queueMock->expects($this->never())->method('sendMessages');

            return $queueMock;
        };

        $container[SynchronizationDependencyProvider::PLUGINS_SYNCHRONIZATION_DATA] = function (Container $container) {
            return $this->createSynchronizationDataPlugins();
        };

        $this->prepareFacade($container);
        $this->synchronizationFacade->executeResolvedPluginsBySources([]);
    }

    /**
     * @return bool
     */
    public function isSuiteProject(): bool
    {
        if (getenv(static::PARAM_PROJECT) === static::PROJECT_SUITE) {
            return true;
        }

        return false;
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
     * @return array
     */
    protected function createSynchronizationDataPlugins()
    {
        return [
            //Search plugins
            new CategoryPageSynchronizationDataPlugin(),
            new CmsPageSynchronizationDataPlugin(),
            new ProductPageSynchronizationDataPlugin(),
            new ProductReviewSearchSynchronizationDataPlugin(),
            new ProductSetSearchSynchronizationDataPlugin(),
            //Storage plugins
            new AvailabilitySynchronizationDataPlugin(),
            new CategoryTreeSynchronizationDataPlugin(),
            new CategoryNodeSynchronizationDataPlugin(),
            new CmsBlockCategorySynchronizationDataPlugin(),
            new CmsBlockProductSynchronizationDataPlugin(),
            new CmsBlockSynchronizationDataPlugin(),
            new CmsSynchronizationDataPlugin(),
            new NavigationSynchronizationDataPlugin(),
            new GlossarySynchronizationDataPlugin(),
            new PriceProductConcreteSynchronizationDataPlugin(),
            new PriceProductAbstractSynchronizationDataPlugin(),
            new ProductCategoryFilterSynchronizationDataPlugin(),
            new ProductCategorySynchronizationDataPlugin(),
            new ProductGroupSynchronizationDataPlugin(),
            new ProductAbstractImageSynchronizationDataPlugin(),
            new ProductConcreteImageSynchronizationDataPlugin(),
            new ProductAbstractLabelSynchronizationDataPlugin(),
            new ProductLabelDictionarySynchronizationDataPlugin(),
            new ProductMeasurementUnitSynchronizationDataPlugin(),
            new ProductConcreteMeasurementUnitSynchronizationDataPlugin(),
            new ProductQuantitySynchronizationDataPlugin(),
            new ProductOptionSynchronizationDataPlugin(),
            new ProductRelationSynchronizationDataPlugin(),
            new ProductReviewSynchronizationDataPlugin(),
            new ProductSearchConfigSynchronizationDataPlugin(),
            new ProductSetSynchronizationDataPlugin(),
            new ProductConcreteSynchronizationDataPlugin(),
            new ProductAbstractSynchronizationDataPlugin(),
            new UrlRedirectSynchronizationDataPlugin(),
            new UrlSynchronizationDataPlugin(),
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
                'setMulti',
                'deleteMulti',
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
                'writeBulk',
                'read',
                'delete',
                'deleteBulk',
            ])
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createUtilEncodingServiceBridgeMock()
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
     * @return \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingServiceBridge
     */
    protected function createUtilEncodingServiceBridge()
    {
        return new SynchronizationToUtilEncodingServiceBridge(
            new UtilEncodingService()
        );
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
