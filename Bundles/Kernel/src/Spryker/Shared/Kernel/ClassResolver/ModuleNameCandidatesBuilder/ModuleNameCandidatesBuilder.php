<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder;

class ModuleNameCandidatesBuilder implements ModuleNameCandidatesBuilderInterface
{
    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder\ModuleNameCandidatesBuilderConfigInterface
     */
    protected $config;

    /**
     * @param \Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder\ModuleNameCandidatesBuilderConfigInterface $config
     */
    public function __construct(ModuleNameCandidatesBuilderConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $moduleName
     *
     * @return array<string>
     */
    public function buildModuleNameCandidates(string $moduleName): array
    {
        $moduleNamePostfix = $this->getApplicationCodeBucket() ?: $this->getCurrentStoreName();

        return [
            $moduleName . $moduleNamePostfix,
            $moduleName,
        ];
    }

    /**
     * @return string
     */
    protected function getApplicationCodeBucket(): string
    {
        return defined('APPLICATION_CODE_BUCKET') ? APPLICATION_CODE_BUCKET : '';
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    protected function getCurrentStoreName(): string
    {
        return $this->config->getCurrentStoreName();
    }
}
