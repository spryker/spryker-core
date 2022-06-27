<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Collector;

use Spryker\Zed\Oauth\Dependency\External\OauthToFilesystemInterface;
use Spryker\Zed\Oauth\Dependency\External\OauthToYamlInterface;
use Spryker\Zed\Oauth\OauthConfig;

class ScopeCacheCollector implements ScopeCacheCollectorInterface
{
    /**
     * @var \Spryker\Zed\Oauth\Dependency\External\OauthToFilesystemInterface
     */
    protected $filesystem;

    /**
     * @var \Spryker\Zed\Oauth\Dependency\External\OauthToYamlInterface
     */
    protected $yamlDumper;

    /**
     * @var array<\Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeCollectorPluginInterface>
     */
    protected $scopeCollectorPlugins;

    /**
     * @var \Spryker\Zed\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @param \Spryker\Zed\Oauth\Dependency\External\OauthToFilesystemInterface $filesystem
     * @param \Spryker\Zed\Oauth\Dependency\External\OauthToYamlInterface $yamlDumper
     * @param array<\Spryker\Glue\OauthExtension\Dependency\Plugin\ScopeCollectorPluginInterface> $scopeCollectorPlugins
     * @param \Spryker\Zed\Oauth\OauthConfig $oauthConfig
     */
    public function __construct(
        OauthToFilesystemInterface $filesystem,
        OauthToYamlInterface $yamlDumper,
        array $scopeCollectorPlugins,
        OauthConfig $oauthConfig
    ) {
        $this->filesystem = $filesystem;
        $this->yamlDumper = $yamlDumper;
        $this->scopeCollectorPlugins = $scopeCollectorPlugins;
        $this->oauthConfig = $oauthConfig;
    }

    /**
     * @return void
     */
    public function collect(): void
    {
        $scopes = [];
        foreach ($this->scopeCollectorPlugins as $scopeCollectorPlugin) {
            foreach ($scopeCollectorPlugin->provideScopes() as $provideScope) {
                if (!isset($scopes[$provideScope->getApplicationNameOrFail()]) || !in_array($provideScope->getIdentifierOrFail(), $scopes[$provideScope->getApplicationNameOrFail()])) {
                    $scopes[$provideScope->getApplicationNameOrFail()][] = $provideScope->getIdentifierOrFail();
                }
            }
        }

        $yaml = $this->yamlDumper->dump($scopes);

        $this->filesystem->dumpFile($this->oauthConfig->getGeneratedFullFileNameForCollectedScopes(), $yaml);
    }
}
