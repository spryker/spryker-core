<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\Fixtures\CodeBucketContext;

use Spryker\Shared\Kernel\CodeBucket\Config\AbstractCodeBucketConfig;

class TestCodeBucketConfigWithDefaultCodeBucket extends AbstractCodeBucketConfig
{
    /**
     * @return string[]
     */
    public function getCodeBuckets(): array
    {
        return [
            'test1',
            'test2',
            'test3',
        ];
    }

    /**
     * @return string
     */
    protected function getDefaultCodeBucket(): string
    {
        return 'test2';
    }
}
