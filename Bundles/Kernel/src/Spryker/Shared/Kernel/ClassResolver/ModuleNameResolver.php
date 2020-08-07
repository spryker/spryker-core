<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

class ModuleNameResolver
{
    /**
     * @param string $moduleName
     *
     * @return string
     */
    public function resolve(string $moduleName): string
    {
        $codeBucket = $this->getCodeBucket();

        $codeBucketIdentifierLength = mb_strlen($codeBucket);
        $codeBucketSuffix = mb_substr($moduleName, -$codeBucketIdentifierLength);

        if ($codeBucketSuffix === $codeBucket) {
            $moduleName = mb_substr($moduleName, 0, -$codeBucketIdentifierLength);
        }

        return $moduleName;
    }

    /**
     * @return string
     */
    protected function getCodeBucket(): string
    {
        return APPLICATION_CODE_BUCKET;
    }
}
