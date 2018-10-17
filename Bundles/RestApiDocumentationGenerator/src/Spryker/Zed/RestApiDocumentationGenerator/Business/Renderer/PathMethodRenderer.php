<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer;

use ArrayObject;
use Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer;
use Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathMethodSpecificationComponent;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathParameterSpecificationComponent;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathRequestSpecificationComponent;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathResponseSpecificationComponent;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface;

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
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface
     */
    protected $specificationComponentValidator;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Validator\SpecificationComponentValidatorInterface $specificationComponentValidator
     */
    public function __construct(SpecificationComponentValidatorInterface $specificationComponentValidator)
    {
        $this->specificationComponentValidator = $specificationComponentValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer
     *
     * @return array
     */
    public function render(RestApiDocumentationPathMethodDataTransfer $pathMethodDataTransfer): array
    {
        $methodComponent = new PathMethodSpecificationComponent();
        $methodComponent->setMethod($pathMethodDataTransfer->getMethod());
        $methodComponent->setSummary($pathMethodDataTransfer->getSummary());
        $methodComponent->addTag($pathMethodDataTransfer->getResource());

        $this->addResponseComponents($methodComponent, $pathMethodDataTransfer->getResponseSchemas());
        $this->addIdParameterComponents($methodComponent, $this->getIdParametersFromResourcePath($pathMethodDataTransfer->getPath()));

        if ($pathMethodDataTransfer->getRequestSchema()) {
            $this->addRequestComponent($methodComponent, $pathMethodDataTransfer->getRequestSchema());
        }

        if ($pathMethodDataTransfer->getIsProtected()) {
            $methodComponent->addSecurity([static::PARAMETER_SECURITY_BEARER_AUTH => []]);
        }

        if ($pathMethodDataTransfer->getHeaders()) {
            $this->addHeaderParameterComponents($methodComponent, $pathMethodDataTransfer->getHeaders());
        }

        return [$pathMethodDataTransfer->getPath() => $methodComponent->toArray()];
    }

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathMethodSpecificationComponent $methodComponent
     * @param \ArrayObject|\Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer[] $responseSchemas
     *
     * @return void
     */
    protected function addResponseComponents(PathMethodSpecificationComponent $methodComponent, ArrayObject $responseSchemas): void
    {
        foreach ($responseSchemas as $responseSchema) {
            $responseComponent = new PathResponseSpecificationComponent();
            $responseComponent->setDescription($responseSchema->getDescription());
            $responseComponent->setCode($responseSchema->getCode());
            if ($responseSchema->getSchemaReference()) {
                $responseComponent->setJsonSchemaRef($responseSchema->getSchemaReference());
            }

            if ($this->specificationComponentValidator->isValid($responseComponent)) {
                $methodComponent->addResponse($responseComponent);
            }
        }
    }

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathMethodSpecificationComponent $methodComponent
     * @param \Generated\Shared\Transfer\RestApiDocumentationPathSchemaDataTransfer $schemaDataTransfer
     *
     * @return void
     */
    protected function addRequestComponent(PathMethodSpecificationComponent $methodComponent, RestApiDocumentationPathSchemaDataTransfer $schemaDataTransfer): void
    {
        $requestComponent = new PathRequestSpecificationComponent();
        $requestComponent->setDescription($schemaDataTransfer->getDescription());
        $requestComponent->setRequired(true);
        $requestComponent->setJsonSchemaRef($schemaDataTransfer->getSchemaReference());

        if ($this->specificationComponentValidator->isValid($requestComponent)) {
            $methodComponent->setRequest($requestComponent);
        }
    }

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathMethodSpecificationComponent $methodComponent
     * @param array $idParameters
     *
     * @return void
     */
    protected function addIdParameterComponents(PathMethodSpecificationComponent $methodComponent, array $idParameters): void
    {
        foreach ($idParameters as $parameter) {
            $parameterComponent = new PathParameterSpecificationComponent();
            $parameterComponent->setName($parameter);
            $parameterComponent->setIn(static::PARAMETER_LOCATION_PATH);
            $parameterComponent->setRequired(true);
            $parameterComponent->setDescription($this->getDescriptionFromIdParameter($parameter));
            $parameterComponent->setSchemaType(static::PARAMETER_SCHEMA_TYPE_STRING);

            if ($this->specificationComponentValidator->isValid($parameterComponent)) {
                $methodComponent->addParameter($parameterComponent);
            }
        }
    }

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Renderer\Component\PathMethodSpecificationComponent $methodComponent
     * @param array $headers
     *
     * @return void
     */
    protected function addHeaderParameterComponents(PathMethodSpecificationComponent $methodComponent, array $headers): void
    {
        foreach ($headers as $header) {
            $parameterComponent = new PathParameterSpecificationComponent();
            $parameterComponent->setName($header);
            $parameterComponent->setIn(static::PARAMETER_LOCATION_HEADER);
            $parameterComponent->setRequired(false);
            $parameterComponent->setSchemaType(static::PARAMETER_SCHEMA_TYPE_STRING);

            if ($this->specificationComponentValidator->isValid($parameterComponent)) {
                $methodComponent->addParameter($parameterComponent);
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
