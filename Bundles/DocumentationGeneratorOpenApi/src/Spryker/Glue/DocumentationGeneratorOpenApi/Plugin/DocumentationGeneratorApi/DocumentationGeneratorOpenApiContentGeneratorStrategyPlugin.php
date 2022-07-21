<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Plugin\DocumentationGeneratorApi;

use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContentGeneratorStrategyPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiFactory getFactory()
 * @method \Spryker\Glue\DocumentationGeneratorOpenApi\DocumentationGeneratorOpenApiConfig getConfig()
 */
class DocumentationGeneratorOpenApiContentGeneratorStrategyPlugin extends AbstractPlugin implements ContentGeneratorStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Generates documentation content in OpenApi format.
     *
     * @api
     *
     * @param array<mixed> $formattedData
     *
     * @return string
     */
    public function generateContent(array $formattedData): string
    {
        return $this->getFactory()
            ->createDocumentationContentGenerator()
            ->writeToYaml($formattedData);
    }
}
