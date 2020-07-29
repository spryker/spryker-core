<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

use Spryker\Shared\Kernel\ClassResolver\ClassNameCandidatesBuilder\ClassNameCandidatesBuilderConfigInterface;
use Spryker\Shared\Kernel\ClassResolver\ModuleNameCandidatesBuilder\ModuleNameCandidatesBuilderConfigInterface;

class KernelConfig extends AbstractSharedConfig implements ClassNameCandidatesBuilderConfigInterface, ModuleNameCandidatesBuilderConfigInterface
{
    /**
     * Set this to true if you want to return already located instances instead of creating new ones for each call.
     *
     * @api
     *
     * @return bool
     */
    public function isLocatorInstanceCacheEnabled(): bool
    {
        return false;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getResolvableCacheFilePath(): string
    {
        $projectNamespaces = implode('/', $this->getProjectOrganizations());

        return APPLICATION_ROOT_DIR . '/src/Generated/Shared/Kernel/' . $projectNamespaces . '/resolvableClassCache.php';
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isResolvableClassNameCacheEnabled(): bool
    {
        return $this->get(KernelConstants::RESOLVABLE_CLASS_NAMES_CACHE_ENABLED, false);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isResolvedInstanceCacheEnabled(): bool
    {
        return $this->get(KernelConstants::RESOLVED_INSTANCE_CACHE_ENABLED, false);
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getProjectOrganizations(): array
    {
        return $this->get(KernelConstants::PROJECT_NAMESPACES);
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getCoreOrganizations(): array
    {
        return $this->get(KernelConstants::CORE_NAMESPACES);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCurrentStoreName(): string
    {
        return APPLICATION_STORE;
    }

    /**
     * Key consist of:
     * - the application name
     * - the resolvable type
     * - the layer (if Zed factory)
     *
     * Value contains placeholders for (ordered):
     * - the organization name
     * - the module candidate name (module, module + store, module + bucket, etc)
     * - the module name
     *
     * @api
     *
     * @return string[]
     */
    public function getResolvableTypeClassNamePatternMap(): array
    {
        return [
            'ClientConfig' => '\\%s\\Client\\%s\\%sConfig',
            'GlueConfig' => '\\%s\\Glue\\%s\\%sConfig',
            'ServiceConfig' => '\\%s\\Service\\%s\\%sConfig',
            'SharedConfig' => '\\%s\\Shared\\%s\\%sConfig',
            'YvesConfig' => '\\%s\\Yves\\%s\\%sConfig',
            'ZedConfig' => '\\%s\\Zed\\%s\\%sConfig',
            'ClientDependencyProvider' => '\\%s\\Client\\%s\\%sDependencyProvider',
            'GlueDependencyProvider' => '\\%s\\Glue\\%s\\%sDependencyProvider',
            'ServiceDependencyProvider' => '\\%s\\Service\\%s\\%sDependencyProvider',
            'YvesDependencyProvider' => '\\%s\\Yves\\%s\\%sDependencyProvider',
            'ZedDependencyProvider' => '\\%s\\Zed\\%s\\%sDependencyProvider',
            'ServiceService' => '\\%s\\Service\\%s\\%sService',
            'ClientClient' => '\\%s\\Client\\%s\\%sClient',
            'ZedFacade' => '\\%s\\Zed\\%s\\Business\\%sFacade',
            'ClientFactory' => '\\%s\\Client\\%s\\%sFactory',
            'GlueFactory' => '\\%s\\Glue\\%s\\%sFactory',
            'GlueResource' => '\\%s\\Glue\\%s\\%sResource',
            'ServiceFactory' => '\\%s\\Service\\%s\\%sServiceFactory',
            'YvesFactory' => '\\%s\\Yves\\%s\\%sFactory',
            'SharedFactory' => '\\%s\\Shared\\%s\\%sSharedFactory',
            'ZedFactoryBusiness' => '\\%s\\Zed\\%s\\Business\\%sBusinessFactory',
            'ZedFactoryCommunication' => '\\%s\\Zed\\%s\\Communication\\%sCommunicationFactory',
            'ZedFactoryPersistence' => '\\%s\\Zed\\%s\\Persistence\\%sPersistenceFactory',
            'ZedQueryContainer' => '\\%s\\Zed\\%s\\Persistence\\%sQueryContainer',
            'ZedEntityManager' => '\\%s\\Zed\\%s\\Persistence\\%sEntityManager',
            'ZedRepository' => '\\%s\\Zed\\%s\\Persistence\\%sRepository',
        ];
    }
}
