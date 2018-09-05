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
//        $this->restApiPathGenerator->addPathsFromAnnotations();
//        $paths = $this->restApiPathGenerator->getPaths();
//        foreach ($paths as $path => $methods) {
//            if (preg_match('/\{([^\}]*)\}[\W]*$/', $path)) {
//                //have id
//                if (isset($methods['get'])) {
//                    //RESPONSE SCHEMA
//                    $schemaRef = $methods['get']['responses']['200']['content']['application/json']['schema']['$ref'];
//                    $schemaRef = explode('/', $schemaRef);
//                    $schemaName = array_pop($schemaRef);
//                    $schemaNameFiltered = str_replace(['Rest', 'Response'], '', $schemaName);
//                    $transferClassName = 'Generated\Shared\Transfer\\' . 'Rest' . $schemaNameFiltered . 'AttributesTransfer';
//                    $this->restApiSchemaGenerator->addSchemaFromTransferClassName($transferClassName);
//
//                    $responseSchemaName = 'Rest' . $schemaNameFiltered . 'Response';
//                    $responseDataSchemaName = $responseSchemaName . 'Data';
//
//                    $this->restApiSchemaGenerator->addResponseSchema($responseSchemaName, $responseDataSchemaName);
//                    $this->restApiSchemaGenerator->addResponseDataSchema($responseDataSchemaName, 'Rest' . $schemaNameFiltered . 'Attributes');
//                }
//                if (isset($methods['patch'])) {
//                    //RESPONSE SCHEMA
//                    $schemaRef = $methods['get']['responses']['200']['content']['application/json']['schema']['$ref'];
//                    $schemaRef = explode('/', $schemaRef);
//                    $schemaName = array_pop($schemaRef);
//                    $schemaNameFiltered = str_replace(['Rest', 'Response', 'List'], '', $schemaName);
//                    $transferClassName = 'Generated\Shared\Transfer\\' . 'Rest' . $schemaNameFiltered . 'AttributesTransfer';
//                    $this->restApiSchemaGenerator->addSchemaFromTransferClassName($transferClassName);
//
//                    $responseSchemaName = 'Rest' . $schemaNameFiltered . 'Response';
//                    $responseDataSchemaName = $responseSchemaName . 'Data';
//
//                    $this->restApiSchemaGenerator->addResponseSchema($responseSchemaName, $responseDataSchemaName);
//                    $this->restApiSchemaGenerator->addResponseDataSchema($responseDataSchemaName, 'Rest' . $schemaNameFiltered . 'Attributes');
//
//                    //REQUEST SCHEMA TBD
//                }
//            } else {
//                //don't have id
//                if (isset($methods['get'])) {
//                    //RESPONSE SCHEMA
//                    $schemaRef = $methods['get']['responses']['200']['content']['application/json']['schema']['$ref'];
//                    $schemaRef = explode('/', $schemaRef);
//                    $schemaName = array_pop($schemaRef);
//                    $schemaNameFiltered = str_replace(['Rest', 'Response', 'List'], '', $schemaName);
//                    $transferClassName = 'Generated\Shared\Transfer\\' . 'Rest' . $schemaNameFiltered . 'AttributesTransfer';
//                    $this->restApiSchemaGenerator->addSchemaFromTransferClassName($transferClassName);
//
//                    $responseSchemaName = 'Rest' . $schemaNameFiltered . 'ListResponse';
//                    $responseDataSchemaName = $responseSchemaName . 'Data';
//
//                    $this->restApiSchemaGenerator->addResponseWithMultipleDataSchema($responseSchemaName, $responseDataSchemaName);
//                    $this->restApiSchemaGenerator->addResponseDataSchema($responseDataSchemaName, 'Rest' . $schemaNameFiltered . 'Attributes');
//                }
//                if (isset($methods['post'])) {
//                    //RESPONSE SCHEMA
//                    $schemaRef = $methods['get']['responses']['200']['content']['application/json']['schema']['$ref'];
//                    $schemaRef = explode('/', $schemaRef);
//                    $schemaName = array_pop($schemaRef);
//                    $schemaNameFiltered = str_replace(['Rest', 'Response', 'List'], '', $schemaName);
//                    $transferClassName = 'Generated\Shared\Transfer\\' . 'Rest' . $schemaNameFiltered . 'AttributesTransfer';
//                    $this->restApiSchemaGenerator->addSchemaFromTransferClassName($transferClassName);
//
//                    $responseSchemaName = 'Rest' . $schemaNameFiltered . 'Response';
//                    $responseDataSchemaName = $responseSchemaName . 'Data';
//
//                    $this->restApiSchemaGenerator->addResponseSchema($responseSchemaName, $responseDataSchemaName);
//                    $this->restApiSchemaGenerator->addResponseDataSchema($responseDataSchemaName, 'Rest' . $schemaNameFiltered . 'Attributes');
//
//                    //REQUEST SCHEMA TBD
//                }
//            }
//        }

        $this->restApiSchemaGenerator->addSchemaFromTransferClassName(RestErrorMessageTransfer::class);

        foreach ($this->resourceRoutesPluginsProviderPlugins as $resourceRoutesPluginsProviderPlugin) {
            foreach ($resourceRoutesPluginsProviderPlugin->getResourceRoutePlugins() as $plugin) {
                $this->restApiSchemaGenerator->addSchemaFromTransferClassName($plugin->getResourceAttributesClassName());
            }
        }

        $this->restApiDocumentationWriter->write(
            $this->restApiPathGenerator->getPaths(),
            $this->restApiSchemaGenerator->getSchemas()
        );
    }
}
