<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestVersionTransfer;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoader;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestTestAttributesTransfer;
use SprykerTest\Glue\GlueApplication\Stub\TestResourceWithParentRoutePlugin;
use SprykerTest\Glue\GlueApplication\Stub\TestVersionableResourceRoutePlugin;
use SprykerTest\Glue\GlueApplication\Stub\TestVersionableResourceWithParentRoutePlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Will be removed without replacement.
 *
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group GlueApplication
 * @group Rest
 * @group ResourceRouteLoaderTest
 *
 * Add your own group annotations below this line
 */
class ResourceRouteLoaderTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_RESOURCE_TYPE = 'tests';

    /**
     * @var string
     */
    protected const TEST_PARENT_RESOURCE_TYPE = 'test-parent-resource-type';

    /**
     * @return void
     */
    public function testLoadShouldConfigureExistingRoute(): void
    {
        $versionResolverMock = $this->createVersionResolverMock();
        $resourceRoutePluginMock = $this->createResourceRoutePluginMock();

        $this->configureBaseRouteMock($resourceRoutePluginMock);

        $resourceRouteLoader = $this->createResourceLoader([$resourceRoutePluginMock], $versionResolverMock);

        $route = $resourceRouteLoader->load('tests', [], Request::create('/tests/1'));

        $this->assertSame('test-resource', $route[RequestConstantsInterface::ATTRIBUTE_CONTROLLER]);
        $this->assertSame('testsRestApi', $route[RequestConstantsInterface::ATTRIBUTE_MODULE]);
        $this->assertSame('tests', $route[RequestConstantsInterface::ATTRIBUTE_TYPE]);
        $this->assertSame('get', $route[RequestConstantsInterface::ATTRIBUTE_CONFIGURATION]['action']);
        $this->assertCount(1, $route[RequestConstantsInterface::ATTRIBUTE_CONFIGURATION]['context']);
        $this->assertTrue($route[RequestConstantsInterface::ATTRIBUTE_CONFIGURATION]['is_protected']);
        $this->assertSame(RestTestAttributesTransfer::class, $route[RequestConstantsInterface::ATTRIBUTE_RESOURCE_FQCN]);
    }

    /**
     * @return void
     */
    public function testLoadUsingVersionableRouteShouldLoadLatest(): void
    {
        $versionResolverMock = $this->createVersionResolverMock();

        $resourceRoutePluginMock1 = $this->createResourceRoutePluginWithVersionMock();
        $this->configureBaseRouteMock($resourceRoutePluginMock1);

        $restVersionTransfer = (new RestVersionTransfer())
            ->setMajor(2)
            ->setMinor(0);

        $resourceRoutePluginMock1->method('getVersion')->willReturn($restVersionTransfer);

        $restVersionTransfer = (new RestVersionTransfer())
            ->setMajor(1)
            ->setMinor(0);

        $resourceRoutePluginMock2 = $this->createResourceRoutePluginWithVersionMock();
        $resourceRoutePluginMock2->method('getVersion')->willReturn($restVersionTransfer);
        $this->configureBaseRouteMock($resourceRoutePluginMock2);

        $resourceRouteLoader = $this->createResourceLoader([$resourceRoutePluginMock1, $resourceRoutePluginMock2], $versionResolverMock);

        /** @var array<\Spryker\Glue\GlueApplication\Rest\Request\Data\VersionInterface> $route */
        $route = $resourceRouteLoader->load('tests', [], Request::create('/tests/1'));

        $this->assertSame(2, $route[RequestConstantsInterface::ATTRIBUTE_RESOURCE_VERSION]->getMajor());
    }

    /**
     * @return void
     */
    public function testLoadUsingVersionableRouteShouldLoadRequestedPlugin(): void
    {
        $versionResolverMock = $this->createVersionResolverMock(1, 0);

        $resourceRoutePluginMock1 = $this->createResourceRoutePluginWithVersionMock();
        $this->configureBaseRouteMock($resourceRoutePluginMock1);

        $restVersionTransfer = (new RestVersionTransfer())
            ->setMajor(2)
            ->setMinor(0);

        $resourceRoutePluginMock1->method('getVersion')->willReturn($restVersionTransfer);

        $restVersionTransfer = (new RestVersionTransfer())
            ->setMajor(1)
            ->setMinor(0);

        $resourceRoutePluginMock2 = $this->createResourceRoutePluginWithVersionMock();
        $resourceRoutePluginMock2->method('getVersion')->willReturn($restVersionTransfer);
        $this->configureBaseRouteMock($resourceRoutePluginMock2);

        $resourceRouteLoader = $this->createResourceLoader([$resourceRoutePluginMock1, $resourceRoutePluginMock2], $versionResolverMock);

        /** @var array<\Spryker\Glue\GlueApplication\Rest\Request\Data\VersionInterface> $route */
        $route = $resourceRouteLoader->load('tests', [], Request::create('/tests/1'));

        $this->assertSame(1, $route[RequestConstantsInterface::ATTRIBUTE_RESOURCE_VERSION]->getMajor());
    }

    /**
     * @return void
     */
    public function testLoadUsingSameResourceNameWithDifferentParents(): void
    {
        $versionResolverMock = $this->createVersionResolverMock();
        $resourceRoutePluginMock1 = $this->createResourceRoutePluginWithParent();
        $this->configureBaseRouteMock($resourceRoutePluginMock1);

        $resourceRoutePluginMock1
            ->method('getParentResourceType')
            ->willReturn('parent-resource1');

        $resourceRoutePluginMock2 = $this->createResourceRoutePluginWithParent();
        $this->configureBaseRouteMock($resourceRoutePluginMock2);

        $resourceRoutePluginMock2
            ->method('getParentResourceType')
            ->willReturn('parent-resource2');

        $resourceRouteLoader = $this->createResourceLoader([$resourceRoutePluginMock1, $resourceRoutePluginMock2], $versionResolverMock);

        $parents = [];
        $parents[][RequestConstantsInterface::ATTRIBUTE_TYPE] = 'parent-resource2';
        $parents[][RequestConstantsInterface::ATTRIBUTE_TYPE] = 'tests';

        $route = $resourceRouteLoader->load('tests', $parents, Request::create('/parent-resource2/2/tests/1'));

        $this->assertSame('test-resource', $route[RequestConstantsInterface::ATTRIBUTE_CONTROLLER]);
        $this->assertSame('testsRestApi', $route[RequestConstantsInterface::ATTRIBUTE_MODULE]);
    }

    /**
     * @return void
     */
    public function testLoadWithVersioningAndResourcesWithParentsShouldReturnRouteWithParent(): void
    {
        // Arrange
        $restVersionTransfer = (new RestVersionTransfer())
            ->setMajor(2)
            ->setMinor(0);

        $versionableResourceRoutePluginMock = $this->createResourceRoutePluginWithVersionMock();
        $this->configureBaseRouteMock($versionableResourceRoutePluginMock);

        $versionableResourceRoutePluginMock
            ->method('getVersion')
            ->willReturn($restVersionTransfer);

        $versionableResourceWithParentRoutePluginMock = $this->createTestVersionableResourceWithParentRoutePlugin(
            $restVersionTransfer,
            static::TEST_PARENT_RESOURCE_TYPE,
        );
        $this->configureBaseRouteMock($versionableResourceWithParentRoutePluginMock);

        $resourceRouteLoader = $this->createResourceLoader(
            [
                $versionableResourceRoutePluginMock, $versionableResourceWithParentRoutePluginMock,
            ],
            $this->createVersionResolverMock(2, 0),
        );

        $resources = [
            [RequestConstantsInterface::ATTRIBUTE_TYPE => static::TEST_PARENT_RESOURCE_TYPE],
            [RequestConstantsInterface::ATTRIBUTE_TYPE => static::TEST_RESOURCE_TYPE],
        ];

        $httpRequest = Request::create(
            sprintf('%s/%s', static::TEST_PARENT_RESOURCE_TYPE, static::TEST_RESOURCE_TYPE),
        );

        // Act
        $resourceRoute = $resourceRouteLoader->load(
            $versionableResourceRoutePluginMock->getResourceType(),
            $resources,
            $httpRequest,
        );

        // Assert
        $this->assertSame(static::TEST_RESOURCE_TYPE, $resourceRoute[RequestConstantsInterface::ATTRIBUTE_TYPE]);
        $this->assertArrayHasKey(RequestConstantsInterface::ATTRIBUTE_PARENT_RESOURCE, $resourceRoute);
        $this->assertSame(static::TEST_PARENT_RESOURCE_TYPE, $resourceRoute[RequestConstantsInterface::ATTRIBUTE_PARENT_RESOURCE]);
    }

    /**
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface> $plugins
     * @param \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface $versionResolverMock
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RouterParameterExpanderPluginInterface> $routerParameterExpanderPlugins
     *
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface
     */
    protected function createResourceLoader(
        array $plugins,
        VersionResolverInterface $versionResolverMock,
        array $routerParameterExpanderPlugins = []
    ): ResourceRouteLoaderInterface {
        return new ResourceRouteLoader($plugins, $versionResolverMock, $routerParameterExpanderPlugins);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface
     */
    protected function createResourceRoutePluginMock(): ResourceRoutePluginInterface
    {
        return $this->getMockBuilder(ResourceRoutePluginInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface
     */
    protected function createResourceRoutePluginWithVersionMock(): ResourceRoutePluginInterface
    {
        return $this->getMockBuilder(TestVersionableResourceRoutePlugin::class)->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface
     */
    protected function createResourceRoutePluginWithParent(): ResourceRoutePluginInterface
    {
        return $this->createMock(TestResourceWithParentRoutePlugin::class);
    }

    /**
     * @param \Generated\Shared\Transfer\RestVersionTransfer $restVersionTransfer
     * @param string $parentResourceType
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface
     */
    protected function createTestVersionableResourceWithParentRoutePlugin(
        RestVersionTransfer $restVersionTransfer,
        string $parentResourceType
    ): ResourceRoutePluginInterface {
        $resourceRoutePluginMock = $this->createMock(TestVersionableResourceWithParentRoutePlugin::class);

        $resourceRoutePluginMock
            ->method('getVersion')
            ->willReturn($restVersionTransfer);

        $resourceRoutePluginMock
            ->method('getParentResourceType')
            ->willReturn($parentResourceType);

        return $resourceRoutePluginMock;
    }

    /**
     * @param int|null $major
     * @param int|null $minor
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface
     */
    protected function createVersionResolverMock(?int $major = null, ?int $minor = null): VersionResolverInterface
    {
        $versionResolverMock = $this->getMockBuilder(VersionResolverInterface::class)
            ->getMock();

        $versionResolverMock
            ->method('findVersion')
            ->willReturn(
                (new RestVersionTransfer())
                    ->setMajor($major)
                    ->setMinor($minor),
            );

        return $versionResolverMock;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface|\PHPUnit\Framework\MockObject\MockObject $resourceRoutePluginMock
     *
     * @return void
     */
    protected function configureBaseRouteMock(ResourceRoutePluginInterface $resourceRoutePluginMock): void
    {
        $resourceRoutePluginMock
            ->method('getResourceType')
            ->willReturn(static::TEST_RESOURCE_TYPE);

        $resourceRoutePluginMock
            ->method('getController')
            ->willReturn('test-resource');

        $resourceRoutePluginMock
            ->method('getModuleName')
            ->willReturn('testsRestApi');

        $resourceRoutePluginMock
            ->method('getResourceAttributesClassName')
            ->willReturn(RestTestAttributesTransfer::class);

        $resourceRoutePluginMock
            ->method('configure')
            ->willReturnCallback(
                function (ResourceRouteCollectionInterface $resourceRouteCollection) {
                    $resourceRouteCollection->addGet('get', true, [1 => 1]);

                    return $resourceRouteCollection;
                },
            );
    }
}
