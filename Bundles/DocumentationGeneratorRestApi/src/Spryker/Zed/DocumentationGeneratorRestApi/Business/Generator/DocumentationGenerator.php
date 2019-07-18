<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzerInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Writer\DocumentationWriterInterface;

class DocumentationGenerator implements DocumentationGeneratorInterface
{
    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzerInterface $resourcePluginAnalyzer
     */
    protected $resourcePluginAnalyzer;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Writer\DocumentationWriterInterface
     */
    protected $documentationWriter;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\ResourcePluginAnalyzerInterface $resourcePluginAnalyzer
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Writer\DocumentationWriterInterface $documentationWriter
     */
    public function __construct(ResourcePluginAnalyzerInterface $resourcePluginAnalyzer, DocumentationWriterInterface $documentationWriter)
    {
        $this->resourcePluginAnalyzer = $resourcePluginAnalyzer;
        $this->documentationWriter = $documentationWriter;
    }

    /**
     * @return void
     */
    public function generateDocumentation(): void
    {
        $this->documentationWriter->write(
            $this->resourcePluginAnalyzer->createRestApiDocumentationFromPlugins()
        );
    }
}
