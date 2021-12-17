<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Kernel\Fixtures\Backend\ClassResolver\RestResource;

use Spryker\Glue\Kernel\Backend\ClassResolver\RestResource\ResourceResolver;

class ResourceResolverStub extends ResourceResolver
{
    /**
     * @var \Spryker\Glue\Kernel\AbstractRestResource
     */
    protected $expectedResult;

    /**
     * @param \Spryker\Glue\Kernel\AbstractRestResource $resource
     *
     * @return void
     */
    public function setExpectedResult($resource)
    {
        $this->expectedResult = $resource;
    }

    /**
     * @param object|string $callerClass
     *
     * @return object|null
     */
    public function doResolve($callerClass)
    {
        if ($this->expectedResult) {
            return $this->expectedResult;
        }

        return parent::doResolve($callerClass);
    }
}
