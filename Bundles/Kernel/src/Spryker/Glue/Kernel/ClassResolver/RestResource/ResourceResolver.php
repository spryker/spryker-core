<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\ClassResolver\RestResource;

use Spryker\Service\Kernel\ClassResolver\AbstractClassResolver;

class ResourceResolver extends AbstractClassResolver
{
    public const CLASS_NAME_PATTERN = '\\%1$s\\Glue\\%2$s%3$s\\%2$sResource';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Glue\Kernel\ClassResolver\RestResource\RestResourceNotFoundException
     *
     * @return \Spryker\Service\Kernel\AbstractService
     */
    public function resolve($callerClass)
    {
        $this->setCallerClass($callerClass);

        if (!$this->canResolve()) {
            throw new RestResourceNotFoundException($this->getClassInfo());
        }

        /** @var \Spryker\Service\Kernel\AbstractService $object */
        $object = $this->getResolvedClassInstance();

        return $object;
    }

    /**
     * @return string
     */
    public function getClassPattern()
    {
        return sprintf(
            self::CLASS_NAME_PATTERN,
            self::KEY_NAMESPACE,
            self::KEY_BUNDLE,
            self::KEY_STORE
        );
    }
}
