<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder;

use Spryker\Shared\Kernel\ClassResolver\ModuleNamePostfixProvider\ModuleNamePostfixProviderInterface;

class ModuleNameCandidatesBuilder implements ModuleNameCandidatesBuilderInterface
{
    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ModuleNamePostfixProvider\ModuleNamePostfixProviderInterface
     */
    protected $moduleNamePostfixProvider;

    /**
     * @param \Spryker\Shared\Kernel\ClassResolver\ModuleNamePostfixProvider\ModuleNamePostfixProviderInterface $moduleNamePostfixProvider
     */
    public function __construct(ModuleNamePostfixProviderInterface $moduleNamePostfixProvider)
    {
        $this->moduleNamePostfixProvider = $moduleNamePostfixProvider;
    }

    /**
     * @param string $moduleName
     * @param string|null $moduleNamePostfix
     *
     * @return array<string>
     */
    public function buildModuleNameCandidates(string $moduleName, ?string $moduleNamePostfix = null): array
    {
        if ($moduleNamePostfix === null) {
            $moduleNamePostfix = $this->moduleNamePostfixProvider->getCurrentModuleNamePostfix();
        }

        return [
            $moduleName . $moduleNamePostfix,
            $moduleName,
        ];
    }
}
