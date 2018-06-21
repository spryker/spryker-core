<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\ClassResolver\Config;

use Spryker\Glue\Kernel\ClassResolver\AbstractClassResolver;

class BundleConfigResolver extends AbstractClassResolver
{
    const CLASS_NAME_PATTERN = '\\%1$s\\Glue\\%2$s%3$s\\%2$sConfig';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Glue\Kernel\ClassResolver\Config\BundleConfigNotFoundException
     *
     * @return \Spryker\Glue\Kernel\AbstractBundleConfig
     */
    public function resolve($callerClass)
    {
        $this->setCallerClass($callerClass);
        if ($this->canResolve()) {
            /** @var \Spryker\Glue\Kernel\AbstractBundleConfig $class */
            $class = $this->getResolvedClassInstance();

            return $class;
        }

        throw new BundleConfigNotFoundException($this->getClassInfo());
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
