<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Cache\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplication\Cache\Writer\ControllerCacheWriterInterface;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\MissingResourceInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

class ControllerCacheReader implements ControllerCacheReaderInterface
{
    /**
     * @var string
     */
    protected const CONTROLLER = '_controller';

    /**
     * @var string
     */
    protected const ROUTE = '_route';

    /**
     * @var \Spryker\Glue\GlueApplication\Cache\Writer\ControllerCacheWriterInterface;
     */
    protected $controllerCacheWriter;

    /**
     * @var \Spryker\Glue\GlueApplication\GlueApplicationConfig
     */
    protected $config;

    /**
     * @param \Spryker\Glue\GlueApplication\Cache\Writer\ControllerCacheWriterInterface $controllerCacheWriter
     * @param \Spryker\Glue\GlueApplication\GlueApplicationConfig $config
     */
    public function __construct(
        ControllerCacheWriterInterface $controllerCacheWriter,
        GlueApplicationConfig $config
    ) {
        $this->controllerCacheWriter = $controllerCacheWriter;
        $this->config = $config;
    }

    /**
     * @param callable(): \Generated\Shared\Transfer\GlueResponseTransfer|array<int, string> $executableResource
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<string, mixed>|null
     */
    public function getActionParameters($executableResource, ResourceInterface $resource, GlueRequestTransfer $glueRequestTransfer): ?array
    {
        if ($resource instanceof MissingResourceInterface) {
            return [];
        }

        $controllerCachePath = $this->config->getControllerCachePath() . DIRECTORY_SEPARATOR . GlueApplicationConfig::API_CONTROLLER_CACHE_FILENAME;
        if (!file_exists($controllerCachePath)) {
            $this->controllerCacheWriter->cache();
        }

        $controllerCache = file_get_contents($controllerCachePath);
        if (!$controllerCache) {
            return null;
        }
        $controllerCache = unserialize($controllerCache);

        /** @phpstan-var array<int, string> $executableResource */
        $controllerConfigurationKey = $this->generateControllerConfigurationKey($executableResource[1], $resource, $glueRequestTransfer);

        if (!isset($controllerCache[$glueRequestTransfer->getApplication()][$controllerConfigurationKey])) {
            return null;
        }

        /**
         * @var \Generated\Shared\Transfer\ApiControllerConfigurationTransfer $apiControllerConfigurationTransfer
         */
        $apiControllerConfigurationTransfer = $controllerCache[$glueRequestTransfer->getApplication()][$controllerConfigurationKey];

        return array_flip($apiControllerConfigurationTransfer->getParameters());
    }

    /**
     * @param string $action
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return string
     */
    protected function generateControllerConfigurationKey(string $action, ResourceInterface $resource, GlueRequestTransfer $glueRequestTransfer): string
    {
        if (!$resource->getType()) {
            return sprintf(
                '%s:%s:%s',
                $glueRequestTransfer->getResource()->getParameters()[static::CONTROLLER][0],
                $glueRequestTransfer->getResource()->getParameters()[static::ROUTE],
                $glueRequestTransfer->getResource()->getParameters()[static::CONTROLLER][1],
            );
        }

        return sprintf('%s:%s:%s', $resource->getController(), $resource->getType(), $action);
    }
}
