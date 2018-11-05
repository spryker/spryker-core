<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Writer\OpenApiSpecificationWriterInterface;

class OpenApiSpecificationGenerator implements OpenApiSpecificationGeneratorInterface
{
    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzerInterface
     */
    protected $resourcePluginAnalyzer;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Writer\OpenApiSpecificationWriterInterface
     */
    protected $openApiSpecificationWriter;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzerInterface $resourcePluginAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Writer\OpenApiSpecificationWriterInterface $openApiSpecificationWriter
     */
    public function __construct(ResourcePluginAnalyzerInterface $resourcePluginAnalyzer, OpenApiSpecificationWriterInterface $openApiSpecificationWriter)
    {
        $this->resourcePluginAnalyzer = $resourcePluginAnalyzer;
        $this->openApiSpecificationWriter = $openApiSpecificationWriter;
    }

    /**
     * @return void
     */
    public function generateOpenApiSpecification(): void
    {
        $this->openApiSpecificationWriter->write(
            $this->resourcePluginAnalyzer->createRestApiDocumentationFromPlugins()
        );
    }
}
