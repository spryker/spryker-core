<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Kernel\Backend\ClassResolver\RestResource;

use Codeception\Test\Unit;
use Spryker\Glue\Kernel\AbstractRestResource as GlueAbstractRestResource;
use Spryker\Glue\Kernel\Backend\AbstractRestResource;
use Spryker\Glue\Kernel\Backend\Exception\InvalidRestResourceException;
use Spryker\Glue\Kernel\ClassResolver\RestResource\RestResourceNotFoundException;
use SprykerTest\Glue\Kernel\Fixtures\Backend\ClassResolver\RestResource\ResourceResolverStub;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Kernel
 * @group Backend
 * @group ClassResolver
 * @group RestResource
 * @group ResourceResolverTest
 * Add your own group annotations below this line
 */
class ResourceResolverTest extends Unit
{
    /**
     * @return void
     */
    public function testMissingResourceThrowsException(): void
    {
        $this->expectException(RestResourceNotFoundException::class);

        $resolver = new ResourceResolverStub();
        $resolver->resolve($this);
    }

    /**
     * @return void
     */
    public function testInvalidResourceThrowsException(): void
    {
        $resolver = new ResourceResolverStub();
        $resolver->setExpectedResult($this->createMock(GlueAbstractRestResource::class));

        $this->expectException(InvalidRestResourceException::class);
        $this->expectExceptionMessage(sprintf('Glue backend resources must extend %s', AbstractRestResource::class));

        $resolver->resolve($this);
    }

    /**
     * @return void
     */
    public function testValidResourceResource(): void
    {
        $expectedResource = $this->createMock(AbstractRestResource::class);

        $resolver = new ResourceResolverStub();
        $resolver->setExpectedResult($expectedResource);

        $this->assertSame($expectedResource, $resolver->resolve($this));
    }
}
