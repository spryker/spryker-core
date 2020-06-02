<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\CodeBucket\Config;

use SprykerConfig\CodeBucketConfig as ProjectCodeBucketConfig;

class CodeBucketConfig implements CodeBucketConfigInterface
{
    /**
     * @var \Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface
     */
    protected $customCodeBucketConfig;

    /**
     * @param \Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface|null $customCodeBucketConfig
     */
    public function __construct(?CodeBucketConfigInterface $customCodeBucketConfig = null)
    {
        $this->initializeCustomCodeBucketConfig($customCodeBucketConfig);
    }

    /**
     * @return string[]
     */
    public function getCodeBuckets(): array
    {
        return $this->customCodeBucketConfig->getCodeBuckets();
    }

    /**
     * @return string
     */
    public function getCurrentCodeBucket(): string
    {
        return $this->customCodeBucketConfig->getCurrentCodeBucket();
    }

    /**
     * @param \Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface|null $customCodeBucketConfig
     *
     * @return void
     */
    protected function initializeCustomCodeBucketConfig(?CodeBucketConfigInterface $customCodeBucketConfig = null): void
    {
        if ($customCodeBucketConfig) {
            $this->customCodeBucketConfig = $customCodeBucketConfig;

            return;
        }

        $this->customCodeBucketConfig = class_exists(ProjectCodeBucketConfig::class) ? new ProjectCodeBucketConfig() : new DefaultCodeBucketConfig();
    }
}
