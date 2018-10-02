<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\GlueApplication\Rest;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\RestVersionTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoader;
use Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface;
use Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceVersionableInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceWithParentPluginInterface;
use SprykerTest\Glue\GlueApplication\Stub\RestTestAttributesTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
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
     * @return void
     */
    public function testLoadShouldConfigureExistingRoute(): void
    {
        $versionResolverMock = $this->createVersionResolverMock();
        $resourceRoutePluginMock = $this->createResourceRoutePluginMock();

        $this->configureBaseRouteMock($resourceRoutePluginMock);

        $resourceRouteLoader = $this->createResourceLoader([$resourceRoutePluginMock], $versionResolverMock);

        $route = $resourceRouteLoader->load('tests', [], Request::create('/tests/1'));

        $this->assertEquals('test-resource', $route[RequestConstantsInterface::ATTRIBUTE_CONTROLLER]);
        $this->assertEquals('testsRestApi', $route[RequestConstantsInterface::ATTRIBUTE_MODULE]);
        $this->assertEquals('tests', $route[RequestConstantsInterface::ATTRIBUTE_TYPE]);
        $this->assertEquals('get', $route[RequestConstantsInterface::ATTRIBUTE_CONFIGURATION]['action']);
        $this->assertCount(1, $route[RequestConstantsInterface::ATTRIBUTE_CONFIGURATION]['context']);
        $this->assertTrue($route[RequestConstantsInterface::ATTRIBUTE_CONFIGURATION]['is_protected']);
        $this->assertEquals(RestTestAttributesTransfer::class, $route[RequestConstantsInterface::ATTRIBUTE_RESOURCE_FQCN]);
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

        $parents[][RequestConstantsInterface::ATTRIBUTE_TYPE] = 'parent-resource2';

        $route = $resourceRouteLoader->load('tests', $parents, Request::create('/tests/1'));

        $this->assertEquals('test-resource', $route[RequestConstantsInterface::ATTRIBUTE_CONTROLLER]);
        $this->assertEquals('testsRestApi', $route[RequestConstantsInterface::ATTRIBUTE_MODULE]);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface[] $plugins
     * @param \Spryker\Glue\GlueApplication\Rest\Version\VersionResolverInterface $versionResolverMock
     *
     * @return \Spryker\Glue\GlueApplication\Rest\ResourceRouteLoaderInterface
     */
    protected function createResourceLoader(
        array $plugins,
        VersionResolverInterface $versionResolverMock
    ): ResourceRouteLoaderInterface {

        return new ResourceRouteLoader($plugins, $versionResolverMock);
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
        return $this->getMockBuilder([
            ResourceRoutePluginInterface::class,
            ResourceVersionableInterface::class,
        ])->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface
     */
    protected function createResourceRoutePluginWithParent(): ResourceRoutePluginInterface
    {
        return $this->getMockBuilder([
            ResourceRoutePluginInterface::class,
            ResourceWithParentPluginInterface::class,
        ])->getMock();
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
            ->setMethods(['findVersion'])
            ->getMock();

        $versionResolverMock
            ->method('findVersion')
            ->willReturn(
                (new RestVersionTransfer())
                    ->setMajor($major)
                    ->setMinor($minor)
            );

        return $versionResolverMock;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $resourceRoutePluginMock
     *
     * @return void
     */
    protected function configureBaseRouteMock(MockObject $resourceRoutePluginMock): void
    {
        $resourceRoutePluginMock
            ->method('getResourceType')
            ->willReturn('tests');

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
                }
            );
    }
}
