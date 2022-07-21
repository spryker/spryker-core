<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi;

use Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\External\DocumentationGeneratorOpenApiToFinderAdapter;
use Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\External\DocumentationGeneratorOpenApiToInflectorAdapter;
use Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\External\DocumentationGeneratorOpenApiToOpenApiAdapter;
use Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\Service\DocumentationGeneratorOpenApiToUtilEncodingServiceBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

/**
 * @method \Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiConfig getConfig()
 */
class DocumentationGeneratorOpenApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const INFLECTOR = 'INFLECTOR';

    /**
     * @var string
     */
    public const OPEN_API_WRITER = 'OPEN_API_WRITER';

    /**
     * @var string
     */
    public const FINDER = 'FINDER';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addInflector($container);
        $container = $this->addOpenApiWriter($container);
        $container = $this->addFinder($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addInflector(Container $container): Container
    {
        $container->set(static::INFLECTOR, function () {
            return new DocumentationGeneratorOpenApiToInflectorAdapter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addOpenApiWriter(Container $container): Container
    {
        $container->set(static::OPEN_API_WRITER, function () {
            return new DocumentationGeneratorOpenApiToOpenApiAdapter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFinder(Container $container): Container
    {
        $container->set(static::FINDER, function () {
            return new DocumentationGeneratorOpenApiToFinderAdapter();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new DocumentationGeneratorOpenApiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }
}
