<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Finder;

use Generated\Shared\Transfer\OauthScopeFindTransfer;
use Spryker\Glue\GlueBackendApiApplication\Dependency\External\GlueBackendApiApplicationToYamlAdapterInterface;
use Spryker\Glue\GlueBackendApiApplication\Exception\CacheFileNotFoundException;
use Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig;

class BackendScopeFinder implements BackendScopeFinderInterface
{
    /**
     * @uses \Spryker\Glue\GlueBackendApiApplication\Plugin\DocumentationGeneratorApi\BackendApiApplicationProviderPlugin::GLUE_BACKEND_API_APPLICATION_NAME
     *
     * @var string
     */
    protected const GLUE_BACKEND_API_APPLICATION_NAME = 'backend';

    /**
     * @var string
     */
    protected const CACHE_FILE_NOT_FOUND_EXCEPTION_MESSAGE = 'Scope collection cache file not found. Please run the following command to generate it: `console oauth:scope-collection-file:generate`';

    /**
     * @var \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig
     */
    protected $config;

    /**
     * @var \Spryker\Glue\GlueBackendApiApplication\Dependency\External\GlueBackendApiApplicationToYamlAdapterInterface
     */
    protected $yamlAdapter;

    /**
     * @param \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig $config
     * @param \Spryker\Glue\GlueBackendApiApplication\Dependency\External\GlueBackendApiApplicationToYamlAdapterInterface $yamlAdapter
     */
    public function __construct(
        GlueBackendApiApplicationConfig $config,
        GlueBackendApiApplicationToYamlAdapterInterface $yamlAdapter
    ) {
        $this->config = $config;
        $this->yamlAdapter = $yamlAdapter;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeFindTransfer $oauthScopeFindTransfer
     *
     * @throws \Spryker\Glue\GlueBackendApiApplication\Exception\CacheFileNotFoundException
     *
     * @return string|null
     */
    public function findScope(OauthScopeFindTransfer $oauthScopeFindTransfer): ?string
    {
        if (!file_exists($this->config->getGeneratedFullFileNameForCollectedScopes())) {
            throw new CacheFileNotFoundException(static::CACHE_FILE_NOT_FOUND_EXCEPTION_MESSAGE);
        }

        $scopes = $this->yamlAdapter->parseFile($this->config->getGeneratedFullFileNameForCollectedScopes());

        if (
            $scopes &&
            isset($scopes[static::GLUE_BACKEND_API_APPLICATION_NAME]) &&
            in_array($oauthScopeFindTransfer->getIdentifier(), $scopes[static::GLUE_BACKEND_API_APPLICATION_NAME])
        ) {
            return $oauthScopeFindTransfer->getIdentifier();
        }

        return null;
    }
}
