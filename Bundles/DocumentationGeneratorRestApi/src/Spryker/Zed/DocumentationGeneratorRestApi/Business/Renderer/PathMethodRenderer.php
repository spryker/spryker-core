<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer;

use ArrayObject;
use Generated\Shared\Transfer\PathMethodComponentTransfer;
use Generated\Shared\Transfer\PathMethodDataTransfer;
use Generated\Shared\Transfer\PathParameterComponentTransfer;
use Generated\Shared\Transfer\PathRequestComponentTransfer;
use Generated\Shared\Transfer\PathResponseComponentTransfer;
use Generated\Shared\Transfer\PathSchemaDataTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathMethodSpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathParameterSpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathRequestSpecificationComponentInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\PathResponseSpecificationComponentInterface;

class PathMethodRenderer implements PathMethodRendererInterface
{
    protected const PATTERN_REGEX_RESOURCE_ID = '/(?<=\{)[\w\-_]+?(?=\})/';
    protected const PATTERN_REGEX_WORD_SLICE = '/(?=[A-Z])/';
    protected const PATTERN_DESCRIPTION_PATH_PARAMETER = 'Id of %s.';

    protected const PARAMETER_LOCATION_PATH = 'path';
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
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return array
     */
    public function render(PathMethodDataTransfer $pathMethodDataTransfer): array
    {
        $pathMethodComponentTransfer = new PathMethodComponentTransfer();
        $pathMethodComponentTransfer->setMethod($pathMethodDataTransfer->getMethod());

        $pathMethodComponentTransfer->setSummary($this->getFormattedSummary($pathMethodDataTransfer));
        $pathMethodComponentTransfer->addTag($pathMethodDataTransfer->getResource());

        $this->addResponseComponents($pathMethodComponentTransfer, $pathMethodDataTransfer->getResponseSchemas());
        $this->addIdParametersFromPath($pathMethodComponentTransfer, $pathMethodDataTransfer->getPath());

        if ($pathMethodDataTransfer->getRequestSchema()) {
            $this->addRequestComponent($pathMethodComponentTransfer, $pathMethodDataTransfer->getRequestSchema());
        }

        if ($pathMethodDataTransfer->getIsProtected()) {
            $pathMethodComponentTransfer->addSecurity([static::PARAMETER_SECURITY_BEARER_AUTH => []]);
        }

        if ($pathMethodDataTransfer->getParameters()->count()) {
            $this->addPathParameterComponents($pathMethodComponentTransfer, $pathMethodDataTransfer);
        }

        $this->pathMethodSpecificationComponent->setPathMethodComponentTransfer($pathMethodComponentTransfer);
        $pathMethodSpecificationData = $this->pathMethodSpecificationComponent->getSpecificationComponentData();

        if ($pathMethodSpecificationData) {
            return [$pathMethodDataTransfer->getPath() => $pathMethodSpecificationData];
        }

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return string
     */
    protected function getFormattedSummary(PathMethodDataTransfer $pathMethodDataTransfer): string
    {
        return implode(PHP_EOL, $pathMethodDataTransfer->getSummary());
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodComponentTransfer $methodComponent
     * @param \ArrayObject $responseSchemas
     *
     * @return void
     */
    protected function addResponseComponents(PathMethodComponentTransfer $methodComponent, ArrayObject $responseSchemas): void
    {
        foreach ($responseSchemas as $responseSchema) {
            $responseComponentTransfer = new PathResponseComponentTransfer();
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
     * @param \Generated\Shared\Transfer\PathMethodComponentTransfer $methodComponent
     * @param \Generated\Shared\Transfer\PathSchemaDataTransfer $schemaDataTransfer
     *
     * @return void
     */
    protected function addRequestComponent(PathMethodComponentTransfer $methodComponent, PathSchemaDataTransfer $schemaDataTransfer): void
    {
        $requestComponentTransfer = new PathRequestComponentTransfer();
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
     * @param \Generated\Shared\Transfer\PathMethodComponentTransfer $methodComponent
     * @param string $path
     *
     * @return void
     */
    protected function addIdParametersFromPath(PathMethodComponentTransfer $methodComponent, string $path): void
    {
        $pathParameters = $this->getPathParametersFromResourcePath($path);

        foreach ($pathParameters as $pathParameter) {
            $parameterComponentTransfer = new PathParameterComponentTransfer();
            $parameterComponentTransfer->setName($pathParameter);
            $parameterComponentTransfer->setIn(static::PARAMETER_LOCATION_PATH);
            $parameterComponentTransfer->setRequired(true);
            $parameterComponentTransfer->setDescription($this->getPathParameterDescription($pathParameter));
            $parameterComponentTransfer->setSchemaType(static::PARAMETER_SCHEMA_TYPE_STRING);

            $this->addPathParameterComponent($methodComponent, $parameterComponentTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodComponentTransfer $methodComponent
     * @param \Generated\Shared\Transfer\PathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return void
     */
    protected function addPathParameterComponents(PathMethodComponentTransfer $methodComponent, PathMethodDataTransfer $pathMethodDataTransfer): void
    {
        foreach ($pathMethodDataTransfer->getParameters() as $parameterComponentTransfer) {
            $this->addPathParameterComponent($methodComponent, $parameterComponentTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PathMethodComponentTransfer $methodComponent
     * @param \Generated\Shared\Transfer\PathParameterComponentTransfer $parameterComponentTransfer
     *
     * @return void
     */
    protected function addPathParameterComponent(PathMethodComponentTransfer $methodComponent, PathParameterComponentTransfer $parameterComponentTransfer): void
    {
        if ($parameterComponentTransfer->getRequired() === null) {
            $parameterComponentTransfer->setRequired(false);
        }
        if ($parameterComponentTransfer->getSchemaType() === null) {
            $parameterComponentTransfer->setSchemaType(static::PARAMETER_SCHEMA_TYPE_STRING);
        }
        $this->pathParameterSpecificationComponent->setPathParameterComponentTransfer($parameterComponentTransfer);
        $pathParameterSpecificationData = $this->pathParameterSpecificationComponent->getSpecificationComponentData();

        if ($pathParameterSpecificationData) {
            $methodComponent->addParameter($pathParameterSpecificationData);
        }
    }

    /**
     * @param string $resourcePath
     *
     * @return array
     */
    protected function getPathParametersFromResourcePath(string $resourcePath): array
    {
        preg_match_all(static::PATTERN_REGEX_RESOURCE_ID, $resourcePath, $matches);

        return $matches[0] ?? [];
    }

    /**
     * @param string $parameter
     *
     * @return string
     */
    protected function getPathParameterDescription(string $parameter): string
    {
        $parameterSplitted = array_slice(preg_split(static::PATTERN_REGEX_WORD_SLICE, $parameter), 0, -1);
        $parameterSplitted = array_map('lcfirst', $parameterSplitted);

        return sprintf(static::PATTERN_DESCRIPTION_PATH_PARAMETER, implode(' ', $parameterSplitted));
    }
}
