<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\Config;

use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

class BundleConfigResolver extends AbstractClassResolver
{
    protected const RESOLVABLE_TYPE = 'ZedConfig';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigNotFoundException
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Zed\Kernel\AbstractBundleConfig $resolved */
        $resolved = $this->doResolve($callerClass);

        if ($resolved !== null) {
            return $resolved;
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
            static::KEY_CODE_BUCKET
        );
    }
}
