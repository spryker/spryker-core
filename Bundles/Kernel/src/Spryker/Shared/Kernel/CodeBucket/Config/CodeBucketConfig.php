<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\CodeBucket\Config;

use SprykerConfig\CodeBucketConfig as SprykerCodeBucketConfig;

class CodeBucketConfig implements CodeBucketConfigInterface
{
    /**
     * @var \Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface
     */
    protected $customCodeBucketContext;

    /**
     * @param \Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface|null $customCodeBucketConfig
     */
    public function __construct(?CodeBucketConfigInterface $customCodeBucketConfig = null)
    {
        $this->customCodeBucketContext = $customCodeBucketConfig ?? new SprykerCodeBucketConfig();
    }

    /**
     * @return string[]
     */
    public function getCodeBuckets(): array
    {
        return $this->resolveCodeBucket()->getCodeBuckets();
    }

    /**
     * @return string
     */
    public function getCurrentCodeBucket(): string
    {
        return $this->resolveCodeBucket()->getCurrentCodeBucket();
    }

    /**
     * @return \Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface
     */
    protected function resolveCodeBucket(): CodeBucketConfigInterface
    {
        return $this->customCodeBucketContext;
    }
}
