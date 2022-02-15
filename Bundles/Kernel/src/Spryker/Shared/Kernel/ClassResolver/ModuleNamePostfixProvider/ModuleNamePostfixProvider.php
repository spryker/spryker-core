<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ModuleNamePostfixProvider;

use Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder\ModuleNameCandidatesBuilderConfigInterface;
use Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface;

class ModuleNamePostfixProvider implements ModuleNamePostfixProviderInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_MODULE_NAME_POSTFIX_VALUE = '';

    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder\ModuleNameCandidatesBuilderConfigInterface
     */
    protected $moduleNameCandidatesBuilderConfig;

    /**
     * @var \Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface
     */
    protected $codeBucketConfig;

    /**
     * @param \Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder\ModuleNameCandidatesBuilderConfigInterface $moduleNameCandidatesBuilderConfig
     * @param \Spryker\Shared\Kernel\CodeBucket\Config\CodeBucketConfigInterface $codeBucketConfig
     */
    public function __construct(
        ModuleNameCandidatesBuilderConfigInterface $moduleNameCandidatesBuilderConfig,
        CodeBucketConfigInterface $codeBucketConfig
    ) {
        $this->moduleNameCandidatesBuilderConfig = $moduleNameCandidatesBuilderConfig;
        $this->codeBucketConfig = $codeBucketConfig;
    }

    /**
     * @return string
     */
    public function getCurrentModuleNamePostfix(): string
    {
        return $this->getCurrentApplicationsCodeBucket() ?: $this->getCurrentStoreName();
    }

    /**
     * @return array<string>
     */
    public function getAvailableModuleNamePostfixes(): array
    {
        $codeBuckets = $this->codeBucketConfig->getCodeBuckets();

        array_unshift($codeBuckets, static::DEFAULT_MODULE_NAME_POSTFIX_VALUE);

        return $codeBuckets;
    }

    /**
     * @return string
     */
    protected function getCurrentApplicationsCodeBucket(): string
    {
        return $this->isApplicationCodeBucketDefined() ? APPLICATION_CODE_BUCKET : '';
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    protected function getCurrentStoreName(): string
    {
        return $this->moduleNameCandidatesBuilderConfig->getCurrentStoreName();
    }

    /**
     * @return bool
     */
    protected function isApplicationCodeBucketDefined(): bool
    {
        return defined('APPLICATION_CODE_BUCKET');
    }
}
