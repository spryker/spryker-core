<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Generator;

use Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\PluginAnalyzerInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface;

class RestApiDocumentationGenerator implements RestApiDocumentationGeneratorInterface
{
    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\PluginAnalyzerInterface
     */
    protected $pluginAnalyzer;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface
     */
    protected $restApiDocumentationWriter;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer\PluginAnalyzerInterface $pluginAnalyzer
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Writer\RestApiDocumentationWriterInterface $restApiDocumentationWriter
     */
    public function __construct(PluginAnalyzerInterface $pluginAnalyzer, RestApiDocumentationWriterInterface $restApiDocumentationWriter)
    {
        $this->pluginAnalyzer = $pluginAnalyzer;
        $this->restApiDocumentationWriter = $restApiDocumentationWriter;
    }

    /**
     * @return void
     */
    public function generateOpenApiSpecification(): void
    {
        $this->pluginAnalyzer->createRestApiDocumentationFromPlugins();
        $this->restApiDocumentationWriter->write(
            $this->pluginAnalyzer->getRestApiDocumentationData()
        );
    }
}
