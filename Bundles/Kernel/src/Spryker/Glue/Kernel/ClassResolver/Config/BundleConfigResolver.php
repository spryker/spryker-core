<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\ClassResolver\Config;

use Spryker\Glue\Kernel\ClassResolver\AbstractClassResolver;

class BundleConfigResolver extends AbstractClassResolver
{
    protected const RESOLVABLE_TYPE = 'GlueConfig';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Glue\Kernel\ClassResolver\Config\BundleConfigNotFoundException
     *
     * @return \Spryker\Glue\Kernel\AbstractBundleConfig
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Glue\Kernel\AbstractBundleConfig $resolved */
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
