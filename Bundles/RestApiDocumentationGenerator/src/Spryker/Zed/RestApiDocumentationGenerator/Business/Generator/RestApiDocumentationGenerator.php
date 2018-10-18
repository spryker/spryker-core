<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourcePluginAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface;

class RestApiDocumentationGenerator implements RestApiDocumentationGeneratorInterface
{
    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourcePluginAnalyzerInterface
     */
    protected $resourcePluginAnalyzer;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface
     */
    protected $restApiDocumentationWriter;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\ResourcePluginAnalyzerInterface $resourcePluginAnalyzer
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface $restApiDocumentationWriter
     */
    public function __construct(ResourcePluginAnalyzerInterface $resourcePluginAnalyzer, RestApiDocumentationWriterInterface $restApiDocumentationWriter)
    {
        $this->resourcePluginAnalyzer = $resourcePluginAnalyzer;
        $this->restApiDocumentationWriter = $restApiDocumentationWriter;
    }

    /**
     * @return void
     */
    public function generateOpenApiSpecification(): void
    {
        $this->restApiDocumentationWriter->write(
            $this->resourcePluginAnalyzer->createRestApiDocumentationFromPlugins()
        );
    }
}
