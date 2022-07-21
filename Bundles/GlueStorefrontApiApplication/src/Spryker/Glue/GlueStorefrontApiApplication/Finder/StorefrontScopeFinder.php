<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueStorefrontApiApplication\Finder;

use Exception;
use Generated\Shared\Transfer\OauthScopeFindTransfer;
use Spryker\Glue\GlueStorefrontApiApplication\Dependency\External\GlueStorefrontApiApplicationToYamlAdapterInterface;
use Spryker\Glue\GlueStorefrontApiApplication\Exception\CacheFileNotFoundException;
use Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig;

class StorefrontScopeFinder implements StorefrontScopeFinderInterface
{
    /**
     * @uses \Spryker\Glue\GlueStorefrontApiApplication\Plugin\GlueApplication\StorefrontApiApplicationProviderPlugin::GLUE_STOREFRONT_API_APPLICATION_NAME
     *
     * @var string
     */
    protected const GLUE_STOREFRONT_API_APPLICATION_NAME = 'storefront';

    /**
     * @var string
     */
    protected const CACHE_FILE_NOT_FOUND_EXCEPTION_MESSAGE = 'Region cache file not found. Please run the following command to generate it: `console oauth:scope-collection-file:generate`';

    /**
     * @var \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig
     */
    protected $config;

    /**
     * @var \Spryker\Glue\GlueStorefrontApiApplication\Dependency\External\GlueStorefrontApiApplicationToYamlAdapterInterface
     */
    protected $yamlAdapter;

    /**
     * @param \Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConfig $config
     * @param \Spryker\Glue\GlueStorefrontApiApplication\Dependency\External\GlueStorefrontApiApplicationToYamlAdapterInterface $yamlAdapter
     */
    public function __construct(
        GlueStorefrontApiApplicationConfig $config,
        GlueStorefrontApiApplicationToYamlAdapterInterface $yamlAdapter
    ) {
        $this->config = $config;
        $this->yamlAdapter = $yamlAdapter;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthScopeFindTransfer $oauthScopeFindTransfer
     *
     * @throws \Spryker\Glue\GlueStorefrontApiApplication\Exception\CacheFileNotFoundException
     *
     * @return string|null
     */
    public function findScope(OauthScopeFindTransfer $oauthScopeFindTransfer): ?string
    {
        try {
            $scopes = $this->yamlAdapter->parseFile($this->config->getGeneratedFullFileNameForCollectedScopes());
        } catch (Exception $e) {
            throw new CacheFileNotFoundException(static::CACHE_FILE_NOT_FOUND_EXCEPTION_MESSAGE, $e->getCode(), $e->getPrevious());
        }

        if (
            $scopes &&
            isset($scopes[static::GLUE_STOREFRONT_API_APPLICATION_NAME]) &&
            in_array($oauthScopeFindTransfer->getIdentifier(), $scopes[static::GLUE_STOREFRONT_API_APPLICATION_NAME])
        ) {
            return $oauthScopeFindTransfer->getIdentifier();
        }

        return null;
    }
}
