<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

/**
 * @deprecated Use {@link \Spryker\Shared\Kernel\ClassResolver\ModuleNameResolver} instead.
 */
class BundleNameResolver
{
    /**
     * @param string $bundleName
     *
     * @return string
     */
    public function resolve($bundleName)
    {
        $codeBucket = $this->getCodeBucket();
        $codeBucketIdentifierLength = mb_strlen($codeBucket);
        $bundleNameSuffix = mb_substr($bundleName, -$codeBucketIdentifierLength);

        if ($bundleNameSuffix === $codeBucket) {
            $bundleName = mb_substr($bundleName, 0, -$codeBucketIdentifierLength);
        }

        return $bundleName;
    }

    /**
     * @return string
     */
    protected function getCodeBucket(): string
    {
        return APPLICATION_CODE_BUCKET;
    }
}
