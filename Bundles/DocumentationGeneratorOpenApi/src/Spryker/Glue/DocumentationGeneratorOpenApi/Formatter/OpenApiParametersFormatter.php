<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\ParameterSchemaTransfer;
use Generated\Shared\Transfer\ParameterTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\ParameterRendererInterface;

class OpenApiParametersFormatter implements OpenApiSchemaFormatterInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_ACCEPT_LANGUAGE_REF_NAME = 'acceptLanguage';

    /**
     * @var string
     */
    protected const DEFAULT_ACCEPT_LANGUAGE_NAME = 'Accept-Language';

    /**
     * @var string
     */
    protected const DEFAULT_ACCEPT_LANGUAGE_IN = 'header';

    /**
     * @var string
     */
    protected const DEFAULT_ACCEPT_LANGUAGE_DESCRIPTION = 'Locale value relevant for the store.';

    /**
     * @var array<mixed>
     */
    protected $parameters = [];

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\ParameterRendererInterface
     */
    protected $parameterSchemeRenderer;

    /**
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\ParameterRendererInterface $parameterSchemeRenderer
     */
    public function __construct(ParameterRendererInterface $parameterSchemeRenderer)
    {
        $this->parameterSchemeRenderer = $parameterSchemeRenderer;

        $this->addAcceptLanguageParameter();
    }

    /**
     * @param array<mixed> $formattedData
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return array<mixed>
     */
    public function format(
        array $formattedData,
        ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
    ): array {
         $this->addAcceptLanguageParameter();

         $formattedData['components']['parameters'] = array_merge_recursive(
             $formattedData['components']['parameters'],
             $this->getParameters(),
         );

         return $formattedData;
    }

    /**
     * @return void
     */
    protected function addAcceptLanguageParameter(): void
    {
        $languageParameter = (new ParameterTransfer())
            ->setRefName(static::DEFAULT_ACCEPT_LANGUAGE_REF_NAME)
            ->setIn(static::DEFAULT_ACCEPT_LANGUAGE_IN)
            ->setDescription(static::DEFAULT_ACCEPT_LANGUAGE_DESCRIPTION)
            ->setName(static::DEFAULT_ACCEPT_LANGUAGE_NAME)
            ->setRequired(false)
            ->setSchema(
                (new ParameterSchemaTransfer())->setType('string'),
            );

        $this->addParameter($languageParameter);
    }

    /**
     * @return array<mixed>
     */
    protected function getParameters(): array
    {
        ksort($this->parameters);

        return $this->parameters;
    }

    /**
     * @param \Generated\Shared\Transfer\ParameterTransfer $parameterTransfer
     *
     * @return void
     */
    protected function addParameter(ParameterTransfer $parameterTransfer): void
    {
        $this->parameters = array_replace_recursive($this->parameters, $this->parameterSchemeRenderer->render($parameterTransfer));
    }
}
