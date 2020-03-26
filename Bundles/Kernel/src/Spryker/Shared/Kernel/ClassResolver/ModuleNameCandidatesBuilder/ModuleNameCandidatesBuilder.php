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
     * @return string[]
     */
    public function buildModuleNameCandidates(string $moduleName): array
    {
        return [
            $moduleName . $this->config->getCurrentStoreName(),
            $moduleName,
        ];
    }
}
