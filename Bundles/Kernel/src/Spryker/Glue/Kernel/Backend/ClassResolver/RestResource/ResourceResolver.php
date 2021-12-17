<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Backend\ClassResolver\RestResource;

use Spryker\Glue\Kernel\Backend\AbstractRestResource;
use Spryker\Glue\Kernel\Backend\Exception\InvalidRestResourceException;
use Spryker\Glue\Kernel\ClassResolver\RestResource\RestResourceNotFoundException;
use Spryker\Service\Kernel\ClassResolver\AbstractClassResolver;

class ResourceResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'GlueResource';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Glue\Kernel\ClassResolver\RestResource\RestResourceNotFoundException
     * @throws \Spryker\Glue\Kernel\Backend\Exception\InvalidRestResourceException
     *
     * @return \Spryker\Glue\Kernel\Backend\AbstractRestResource
     */
    public function resolve($callerClass)
    {
        $resolved = $this->doResolve($callerClass);

        if ($resolved === null) {
            throw new RestResourceNotFoundException($this->getClassInfo());
        }

        if (!$resolved instanceof AbstractRestResource) {
            throw new InvalidRestResourceException(sprintf('Glue backend resources must extend %s', AbstractRestResource::class));
        }

        return $resolved;
    }
}
