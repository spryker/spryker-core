<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface;

class RestApiDocumentationGenerator implements RestApiDocumentationGeneratorInterface
{
    /**
     * @var \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[]
     */
    protected $resourceRoutesPluginsProviderPlugins;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface
     */
    protected $restApiSchemaGenerator;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface
     */
    protected $restApiPathGenerator;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface
     */
    protected $restApiDocumentationWriter;

    /**
     * @param \Spryker\Glue\RestApiDocumentationGeneratorExtension\Dependency\Plugin\ResourceRoutePluginsProviderPluginInterface[] $resourceRoutesPluginsProviderPlugins
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationSchemaGeneratorInterface $restApiSchemaGenerator
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Generator\RestApiDocumentationPathGeneratorInterface $restApiPathGenerator
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface $restApiDocumentationWriter
     */
    public function __construct(
        array $resourceRoutesPluginsProviderPlugins,
        RestApiDocumentationSchemaGeneratorInterface $restApiSchemaGenerator,
        RestApiDocumentationPathGeneratorInterface $restApiPathGenerator,
        RestApiDocumentationWriterInterface $restApiDocumentationWriter
    ) {
        $this->resourceRoutesPluginsProviderPlugins = $resourceRoutesPluginsProviderPlugins;
        $this->restApiSchemaGenerator = $restApiSchemaGenerator;
        $this->restApiPathGenerator = $restApiPathGenerator;
        $this->restApiDocumentationWriter = $restApiDocumentationWriter;
    }

    /**
     * @return void
     */
    public function generateOpenApiSpecification(): void
    {
        $this->restApiSchemaGenerator->addSchemaFromTransferClassName(RestErrorMessageTransfer::class);
        $errorTransferSchemaKey = $this->restApiSchemaGenerator->getLastAddedSchemaKey();

        foreach ($this->resourceRoutesPluginsProviderPlugins as $resourceRoutesPluginsProviderPlugin) {
            foreach ($resourceRoutesPluginsProviderPlugin->getResourceRoutePlugins() as $plugin) {
                $this->restApiSchemaGenerator->addSchemaFromTransferClassName($plugin->getResourceAttributesClassName());
                $transferSchemaKey = $this->restApiSchemaGenerator->getLastAddedSchemaKey();
                $this->restApiPathGenerator->addPathsForPlugin($plugin, $transferSchemaKey, $errorTransferSchemaKey);
            }
        }

        $this->restApiDocumentationWriter->write(
            $this->restApiPathGenerator->getPaths(),
            $this->restApiSchemaGenerator->getSchemas()
        );
    }
}
