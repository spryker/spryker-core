<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication;

use Spryker\Glue\GlueApplication\Dependency\Client\GlueApplicationToStoreClientBridge;
use Spryker\Glue\GlueApplication\Dependency\Service\GlueApplicationToUtilEncodingServiceBridge;
use Spryker\Glue\GlueApplication\Rest\Collection\ResourceRelationshipCollection;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\Kernel\Plugin\Pimple;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationConfig getConfig()
 */
class GlueApplicationDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGIN_RESOURCE_ROUTES = 'PLUGIN_RESOURCE_ROUTES';
    public const PLUGIN_RESOURCE_RELATIONSHIP = 'PLUGIN_RESOURCE_RELATIONSHIP';
    public const PLUGIN_VALIDATE_HTTP_REQUEST = 'PLUGIN_VALIDATE_HTTP_REQUEST';
    public const PLUGIN_VALIDATE_REST_REQUEST = 'PLUGIN_VALIDATE_REST_REQUEST';
    public const PLUGIN_REST_REQUEST_VALIDATOR = 'PLUGIN_REST_REQUEST_VALIDATOR';
    public const PLUGIN_FORMAT_REQUEST = 'PLUGIN_FORMAT_REQUEST';
    public const PLUGIN_FORMAT_RESPONSE_DATA = 'PLUGIN_FORMAT_RESPONSE_DATA';
    public const PLUGIN_FORMAT_RESPONSE_HEADERS = 'PLUGIN_FORMAT_RESPONSE_HEADERS';
    public const PLUGIN_CONTROLLER_BEFORE_ACTION = 'PLUGIN_CONTROLLER_BEFORE_ACTION';
    public const PLUGIN_CONTROLLER_AFTER_ACTION = 'PLUGIN_CONTROLLER_AFTER_ACTION';
    public const PLUGINS_APPLICATION = 'PLUGINS_APPLICATION';
    public const PLUGINS_REST_USER_FINDER = 'PLUGINS_REST_USER_FINDER';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const CLIENT_STORE = 'CLIENT_STORE';
    public const APPLICATION_GLUE = 'APPLICATION_GLUE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addUtilEncodingService($container);
        $container = $this->addGlueApplication($container);
        $container = $this->addStoreClient($container);

        $container = $this->addResourceRoutePlugins($container);
        $container = $this->addResourceRelationshipPlugins($container);
        $container = $this->addValidateHttpRequestPlugins($container);
        $container = $this->addValidateRestRequestPlugins($container);
        $container = $this->addRestRequestValidatorPlugins($container);
        $container = $this->addFormatRequestPlugins($container);
        $container = $this->addFormatResponseDataPlugins($container);
        $container = $this->addFormatResponseHeadersPlugins($container);
        $container = $this->addControllerBeforeActionPlugins($container);
        $container = $this->addControllerAfterActionPlugins($container);
        $container = $this->addApplicationPlugins($container);
        $container = $this->addRestUserFinderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlueApplication(Container $container): Container
    {
        $container[static::APPLICATION_GLUE] = function (Container $container) {
            return (new Pimple())->getApplication();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new GlueApplicationToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResourceRoutePlugins(Container $container): Container
    {
        $container[static::PLUGIN_RESOURCE_ROUTES] = function (Container $container) {
            return $this->getResourceRoutePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addResourceRelationshipPlugins(Container $container): Container
    {
        $container[static::PLUGIN_RESOURCE_RELATIONSHIP] = function (Container $container) {
            return $this->getResourceRelationshipPlugins(new ResourceRelationshipCollection());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addValidateHttpRequestPlugins(Container $container): Container
    {
        $container[static::PLUGIN_VALIDATE_HTTP_REQUEST] = function (Container $container) {
            return $this->getValidateHttpRequestPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addValidateRestRequestPlugins(Container $container): Container
    {
        $container[static::PLUGIN_VALIDATE_REST_REQUEST] = function (Container $container) {
            return $this->getValidateRestRequestPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestRequestValidatorPlugins(Container $container): Container
    {
        $container[static::PLUGIN_REST_REQUEST_VALIDATOR] = function (Container $container) {
            return $this->getRestRequestValidatorPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFormatRequestPlugins(Container $container): Container
    {
        $container[static::PLUGIN_FORMAT_REQUEST] = function (Container $container) {
            return $this->getFormatRequestPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFormatResponseDataPlugins(Container $container): Container
    {
        $container[static::PLUGIN_FORMAT_RESPONSE_DATA] = function (Container $container) {
            return $this->getFormatResponseDataPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addFormatResponseHeadersPlugins(Container $container): Container
    {
        $container[static::PLUGIN_FORMAT_RESPONSE_HEADERS] = function (Container $container) {
            return $this->getFormatResponseHeadersPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container[static::CLIENT_STORE] = function (Container $container) {
            return new GlueApplicationToStoreClientBridge($container->getLocator()->store()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addControllerBeforeActionPlugins(Container $container): Container
    {
        $container[static::PLUGIN_CONTROLLER_BEFORE_ACTION] = function (Container $container) {
            return $this->getControllerBeforeActionPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addControllerAfterActionPlugins(Container $container): Container
    {
        $container[static::PLUGIN_CONTROLLER_AFTER_ACTION] = function (Container $container) {
            return $this->getControllerAfterActionPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addApplicationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_APPLICATION, function (Container $container): array {
            return $this->getApplicationPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestUserFinderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_REST_USER_FINDER] = function (Container $container) {
            return $this->getRestUserFinderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getApplicationPlugins(): array
    {
        return [];
    }

    /**
     * Rest resource route plugin stack
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface[]
     */
    protected function getResourceRoutePlugins(): array
    {
        return [];
    }

    /**
     * Rest resource relation provider plugin collection, plugins must construct full resource by resource ids.
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface $resourceRelationshipCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    protected function getResourceRelationshipPlugins(
        ResourceRelationshipCollectionInterface $resourceRelationshipCollection
    ): ResourceRelationshipCollectionInterface {
        return $resourceRelationshipCollection;
    }

    /**
     * Validate http request plugins
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[]
     */
    protected function getValidateHttpRequestPlugins(): array
    {
        return [];
    }

    /**
     * Format/Parse http request to internal rest resource request
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatRequestPluginInterface[]
     */
    protected function getFormatRequestPlugins(): array
    {
        return [];
    }

    /**
     * Format response data the data which will send with http response
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseDataPluginInterface[]
     */
    protected function getFormatResponseDataPlugins(): array
    {
        return [];
    }

    /**
     * Format/add additional response headers
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseHeadersPluginInterface[]
     */
    protected function getFormatResponseHeadersPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface[]
     */
    protected function getValidateRestRequestPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestRequestValidatorPluginInterface[]
     */
    protected function getRestRequestValidatorPlugins(): array
    {
        return [];
    }

    /**
     * Called before invoking controller action
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface[]
     */
    protected function getControllerBeforeActionPlugins(): array
    {
        return [];
    }

    /**
     * Called after done processing controller action
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerAfterActionPluginInterface[]
     */
    protected function getControllerAfterActionPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserFinderPluginInterface[]
     */
    protected function getRestUserFinderPlugins(): array
    {
        return [];
    }
}
