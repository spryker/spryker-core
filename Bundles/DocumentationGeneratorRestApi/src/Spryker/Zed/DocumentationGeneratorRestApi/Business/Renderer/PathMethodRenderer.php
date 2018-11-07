<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer;

use ArrayObject;
use Generated\Shared\Transfer\OpenApiSpecificationPathMethodComponentTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationPathParameterComponentTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationPathRequestComponentTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationPathResponseComponentTransfer;
use Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathMethodSpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathParameterSpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathRequestSpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathResponseSpecificationComponentInterface;

class PathMethodRenderer implements PathMethodRendererInterface
{
    protected const PATTERN_REGEX_RESOURCE_ID = '/(?<=\{)[\w-_]+?(?=\})/';
    protected const PATTERN_REGEX_WORD_SLICE = '/(?=[A-Z])/';
    protected const PATTERN_DESCRIPTION_PARAMETER_ID = 'Id of %s.';

    protected const PARAMETER_LOCATION_PATH = 'path';
    protected const PARAMETER_LOCATION_HEADER = 'header';
    protected const PARAMETER_SCHEMA_TYPE_STRING = 'string';
    protected const PARAMETER_SECURITY_BEARER_AUTH = 'BearerAuth';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathMethodSpecificationComponentInterface
     */
    protected $pathMethodSpecificationComponent;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathResponseSpecificationComponentInterface
     */
    protected $pathResponseSpecificationComponent;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathRequestSpecificationComponentInterface
     */
    protected $pathRequestSpecificationComponent;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathParameterSpecificationComponentInterface
     */
    protected $pathParameterSpecificationComponent;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathMethodSpecificationComponentInterface $pathMethodSpecificationComponent
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathResponseSpecificationComponentInterface $pathResponseSpecificationComponent
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathRequestSpecificationComponentInterface $pathRequestSpecificationComponent
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathParameterSpecificationComponentInterface $pathParameterSpecificationComponent
     */
    public function __construct(
        PathMethodSpecificationComponentInterface $pathMethodSpecificationComponent,
        PathResponseSpecificationComponentInterface $pathResponseSpecificationComponent,
        PathRequestSpecificationComponentInterface $pathRequestSpecificationComponent,
        PathParameterSpecificationComponentInterface $pathParameterSpecificationComponent
    ) {
        $this->pathMethodSpecificationComponent = $pathMethodSpecificationComponent;
        $this->pathResponseSpecificationComponent = $pathResponseSpecificationComponent;
        $this->pathRequestSpecificationComponent = $pathRequestSpecificationComponent;
        $this->pathParameterSpecificationComponent = $pathParameterSpecificationComponent;
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return array
     */
    public function render(OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer): array
    {
        $pathMethodComponentTransfer = new OpenApiSpecificationPathMethodComponentTransfer();
        $pathMethodComponentTransfer->setMethod($pathMethodDataTransfer->getMethod());

        $pathMethodComponentTransfer->setSummary($this->getFormattedSummary($pathMethodDataTransfer));
        $pathMethodComponentTransfer->addTag($pathMethodDataTransfer->getResource());

        $this->addResponseComponents($pathMethodComponentTransfer, $pathMethodDataTransfer->getResponseSchemas());
        $this->addIdParameterComponents($pathMethodComponentTransfer, $this->getIdParametersFromResourcePath($pathMethodDataTransfer->getPath()));

        if ($pathMethodDataTransfer->getRequestSchema()) {
            $this->addRequestComponent($pathMethodComponentTransfer, $pathMethodDataTransfer->getRequestSchema());
        }

        if ($pathMethodDataTransfer->getIsProtected()) {
            $pathMethodComponentTransfer->addSecurity([static::PARAMETER_SECURITY_BEARER_AUTH => []]);
        }

        if ($pathMethodDataTransfer->getHeaders()) {
            $this->addHeaderParameterComponents($pathMethodComponentTransfer, $pathMethodDataTransfer->getHeaders());
        }

        $this->pathMethodSpecificationComponent->setPathMethodComponentTransfer($pathMethodComponentTransfer);
        $pathMethodSpecificationData = $this->pathMethodSpecificationComponent->getSpecificationComponentData();

        if ($pathMethodSpecificationData) {
            return [$pathMethodDataTransfer->getPath() => $pathMethodSpecificationData];
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return string
     */
    protected function getFormattedSummary(OpenApiSpecificationPathMethodDataTransfer $pathMethodDataTransfer): string
    {
        return implode(PHP_EOL, $pathMethodDataTransfer->getSummary());
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodComponentTransfer $methodComponent
     * @param \ArrayObject $responseSchemas
     *
     * @return void
     */
    protected function addResponseComponents(OpenApiSpecificationPathMethodComponentTransfer $methodComponent, ArrayObject $responseSchemas): void
    {
        foreach ($responseSchemas as $responseSchema) {
            $responseComponentTransfer = new OpenApiSpecificationPathResponseComponentTransfer();
            $responseComponentTransfer->setDescription($responseSchema->getDescription());
            $responseComponentTransfer->setCode($responseSchema->getCode());
            if ($responseSchema->getSchemaReference()) {
                $responseComponentTransfer->setJsonSchemaRef($responseSchema->getSchemaReference());
            }

            $this->pathResponseSpecificationComponent->setPathResponseComponentTransfer($responseComponentTransfer);
            $pathResponseSpecificationData = $this->pathResponseSpecificationComponent->getSpecificationComponentData();

            if ($pathResponseSpecificationData) {
                $methodComponent->addResponse($pathResponseSpecificationData);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodComponentTransfer $methodComponent
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathSchemaDataTransfer $schemaDataTransfer
     *
     * @return void
     */
    protected function addRequestComponent(OpenApiSpecificationPathMethodComponentTransfer $methodComponent, OpenApiSpecificationPathSchemaDataTransfer $schemaDataTransfer): void
    {
        $requestComponentTransfer = new OpenApiSpecificationPathRequestComponentTransfer();
        $requestComponentTransfer->setDescription($schemaDataTransfer->getDescription());
        $requestComponentTransfer->setRequired(true);
        $requestComponentTransfer->setJsonSchemaRef($schemaDataTransfer->getSchemaReference());

        $this->pathRequestSpecificationComponent->setPathRequestComponentTransfer($requestComponentTransfer);
        $pathRequestSpecificationData = $this->pathRequestSpecificationComponent->getSpecificationComponentData();

        if ($pathRequestSpecificationData) {
            $methodComponent->setRequest($pathRequestSpecificationData);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodComponentTransfer $methodComponent
     * @param array $idParameters
     *
     * @return void
     */
    protected function addIdParameterComponents(OpenApiSpecificationPathMethodComponentTransfer $methodComponent, array $idParameters): void
    {
        foreach ($idParameters as $parameter) {
            $parameterComponentTransfer = new OpenApiSpecificationPathParameterComponentTransfer();
            $parameterComponentTransfer->setName($parameter);
            $parameterComponentTransfer->setIn(static::PARAMETER_LOCATION_PATH);
            $parameterComponentTransfer->setRequired(true);
            $parameterComponentTransfer->setDescription($this->getDescriptionFromIdParameter($parameter));
            $parameterComponentTransfer->setSchemaType(static::PARAMETER_SCHEMA_TYPE_STRING);

            $this->pathParameterSpecificationComponent->setPathParameterComponentTransfer($parameterComponentTransfer);
            $pathParameterSpecificationData = $this->pathParameterSpecificationComponent->getSpecificationComponentData();

            if ($pathParameterSpecificationData) {
                $methodComponent->addParameter($pathParameterSpecificationData);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiSpecificationPathMethodComponentTransfer $methodComponent
     * @param array $headers
     *
     * @return void
     */
    protected function addHeaderParameterComponents(OpenApiSpecificationPathMethodComponentTransfer $methodComponent, array $headers): void
    {
        foreach ($headers as $header) {
            $parameterComponentTransfer = new OpenApiSpecificationPathParameterComponentTransfer();
            $parameterComponentTransfer->setName($header);
            $parameterComponentTransfer->setIn(static::PARAMETER_LOCATION_HEADER);
            $parameterComponentTransfer->setRequired(false);
            $parameterComponentTransfer->setSchemaType(static::PARAMETER_SCHEMA_TYPE_STRING);

            $this->pathParameterSpecificationComponent->setPathParameterComponentTransfer($parameterComponentTransfer);
            $pathParameterSpecificationData = $this->pathParameterSpecificationComponent->getSpecificationComponentData();

            if ($pathParameterSpecificationData) {
                $methodComponent->addParameter($pathParameterSpecificationData);
            }
        }
    }

    /**
     * @param string $resourcePath
     *
     * @return array
     */
    protected function getIdParametersFromResourcePath(string $resourcePath): array
    {
        preg_match_all(static::PATTERN_REGEX_RESOURCE_ID, $resourcePath, $matches);

        return $matches[0] ?? [];
    }

    /**
     * @param string $parameter
     *
     * @return string
     */
    protected function getDescriptionFromIdParameter(string $parameter): string
    {
        $parameterSplitted = array_slice(preg_split(static::PATTERN_REGEX_WORD_SLICE, $parameter), 0, -1);
        $parameterSplitted = array_map('lcfirst', $parameterSplitted);

        return sprintf(static::PATTERN_DESCRIPTION_PARAMETER_ID, implode(' ', $parameterSplitted));
    }
}
