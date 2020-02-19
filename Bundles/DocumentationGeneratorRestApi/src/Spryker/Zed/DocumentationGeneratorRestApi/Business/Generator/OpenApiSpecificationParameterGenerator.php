<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Generator;

use Generated\Shared\Transfer\ParameterSchemaTransfer;
use Generated\Shared\Transfer\ParameterTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\ParameterRendererInterface;

class OpenApiSpecificationParameterGenerator implements ParameterGeneratorInterface
{
    protected const PATTERN__REFERENCE = '#/components/parameters/%s';

    protected const DEFAULT_LANGUAGE_REF_NAME = 'acceptLanguage';
    protected const DEFAULT_LANGUAGE_NAME = 'Accept-Language';
    protected const DEFAULT_LANGUAGE_IN = 'header';
    protected const DEFAULT_LANGUAGE_DESCRIPTION = 'Locale value relevant for the store.';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\ParameterRendererInterface
     */
    protected $parameterSchemeRenderer;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\ParameterRendererInterface $parameterSchemeRenderer
     */
    public function __construct(ParameterRendererInterface $parameterSchemeRenderer)
    {
        $this->parameterSchemeRenderer = $parameterSchemeRenderer;

        $this->addDefaultParameters();
    }

    /**
     * @return array
     */
    public function getParameters(): array
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

    /**
     * @return void
     */
    protected function addDefaultParameters(): void
    {
        $this->addDefaultLanguageParameter();
    }

    /**
     * @return void
     */
    protected function addDefaultLanguageParameter(): void
    {
        $language = $this->createParameter(
            static::DEFAULT_LANGUAGE_REF_NAME,
            static::DEFAULT_LANGUAGE_IN,
            static::DEFAULT_LANGUAGE_DESCRIPTION,
            static::DEFAULT_LANGUAGE_NAME
        );

        $this->addParameter($language);
    }

    /**
     * @param string $refName
     * @param string $in
     * @param string $description
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\ParameterTransfer
     */
    protected function createParameter(string $refName, string $in, string $description, string $name): ParameterTransfer
    {
        $parameter = new ParameterTransfer();
        $parameter->setRefName($refName);
        $parameter->setIn($in);
        $parameter->setDescription($description);
        $parameter->setName($name);
        $parameter->setRequired(false);
        $parameter->setSchema($this->createParameterScheme('string'));

        return $parameter;
    }

    /**
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\ParameterSchemaTransfer
     */
    protected function createParameterScheme(string $type): ParameterSchemaTransfer
    {
        $parameterSchemaTransfer = new ParameterSchemaTransfer();
        $parameterSchemaTransfer->setType($type);

        return $parameterSchemaTransfer;
    }
}
