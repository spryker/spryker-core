<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer;

use Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\Service\DocumentationGeneratorOpenApiToUtilEncodingServiceInterface;
use Spryker\Glue\DocumentationGeneratorOpenApi\Exception\InvalidAnnotationFormatException;
use Spryker\Glue\DocumentationGeneratorOpenApi\Finder\FinderInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * Specification
 *  - Parses a .php file and checks if it contains a PHPDock annotation in the specific format of Glue annotations.
 *  - Glue annotation starts with @Glue tag and all the parameters are written inside brackets in JSON format, e.g.:
 *  - @Glue({"getResourceById": {"summary": ["Some endpoint summary"]}}) will define summary for endpoint's GET method.
 *  - Top-level elements in JSON objects should start with defining the method they're related to.
 *  - Method could be one of the following:
 *      - getResourceById - GET method, that returns exactly one resource and should contain id in path;
 *      - getCollection - GET method, that returns collection of resources;
 *      - post, patch, delete - respectively POST, PATCH and DELETE methods representation.
 *  - The method's parameters may consist of:
 *      - "path" - path to action;
 *      - "summary" - method's summary;
 *      - "parameters" - additional parameters, that can be passed with request in query, header, path or cookie;
 *      - "responseAttributesClassName" - defines FQCN of transfer, that represents response object;
 *      - "isEmptyResponse" - defines is endpoint doesn't have a response body data (e.g. return 204 HTTP status code)
 *      - "responses" - JSON object that contain list of possible errors in format "code": "message"
 *      - "isIdNullable" - sets if `id` will be nullable;
 *      - "deprecated" - sets if method is deprecated;
 */
class AnnotationAnalyzer implements AnnotationAnalyzerInterface
{
    /**
     * @var string
     */
    protected const PATTERN_REGEX_GLUE_ANNOTATION = '/(?<=@Glue\()(.|\n)*?(?=(\s\*\n)*?\))/';

    /**
     * @var string
     */
    protected const EXCEPTION_MESSAGE_INVALID_ANNOTATION_FORMAT = 'Invalid JSON format: %s in %s';

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Finder\FinderInterface
     */
    protected $glueFileFinder;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\Service\DocumentationGeneratorOpenApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Finder\FinderInterface $glueFileFinder
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Dependency\Service\DocumentationGeneratorOpenApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        FinderInterface $glueFileFinder,
        DocumentationGeneratorOpenApiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->glueFileFinder = $glueFileFinder;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $classPath
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $annotationsTransfer
     * @param string|null $actionName
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    public function getResourceParametersFromResource(
        string $classPath,
        AbstractTransfer $annotationsTransfer,
        ?string $actionName = null
    ): ?AbstractTransfer {
        $glueControllerFiles = $this->glueFileFinder->getFilesFromClassPath($classPath);

        $parameters = [];
        foreach ($glueControllerFiles as $file) {
            /** @var string $rawFileInput */
            $rawFileInput = file_get_contents($file);
            $tokens = token_get_all($rawFileInput);
            $parameters[] = $this->parsePhpTokens($tokens, $actionName);
        }
        if (isset($parameters[0]) && !array_filter($parameters[0])) {
            return null;
        }

        if ($parameters === []) {
            return null;
        }

        /** @var \Generated\Shared\Transfer\PathAnnotationTransfer|\Generated\Shared\Transfer\RelationshipPluginAnnotationsContextTransfer $annotationsTransfer */
        $annotationsTransfer = $annotationsTransfer->fromArray(array_replace_recursive(...$parameters), true);

        return $annotationsTransfer;
    }

    /**
     * @param array<int, mixed> $phpTokens
     * @param string|null $actionName
     *
     * @return array<mixed>
     */
    protected function parsePhpTokens(array $phpTokens, ?string $actionName = null): array
    {
        $result = [];
        $phpTokens = array_filter($phpTokens, 'is_array');
        foreach ($phpTokens as $key => $phpToken) {
            if ($phpToken[0] !== T_DOC_COMMENT) {
                continue;
            }

            if (
                $actionName
                && $this->findNextFunctionName($phpTokens, $key) !== $actionName
            ) {
                continue;
            }

            $annotationsParsed = $this->getDocCommentParameters($phpToken[1]);
            if ($annotationsParsed) {
                $result = $this->getDataFromParsedAnnotations($annotationsParsed, $result);
            }
        }

        return $result;
    }

    /**
     * @param array<int, mixed> $phpTokens
     * @param int $currentKey
     *
     * @return string|null
     */
    protected function findNextFunctionName(array $phpTokens, int $currentKey): ?string
    {
        $needle = T_FUNCTION;

        while (isset($phpTokens[$currentKey])) {
            if ($phpTokens[$currentKey][0] !== $needle) {
                $currentKey++;

                continue;
            }

            if ($needle === T_STRING) {
                $var = $phpTokens[$currentKey][1];

                return $var;
            }

            $needle = T_STRING;
            $currentKey++;
        }

        return null;
    }

    /**
     * @param string $comment
     *
     * @return array<int, mixed>|null
     */
    protected function getDocCommentParameters(string $comment): ?array
    {
        if (!preg_match_all(static::PATTERN_REGEX_GLUE_ANNOTATION, $comment, $matches)) {
            return null;
        }

        $matches = array_map('trim', $matches[0]);
        $matchesFiltered = array_filter($matches, 'strlen');
        if (!$matchesFiltered) {
            return null;
        }

        return $this->transformAnnotationsToArray($matchesFiltered);
    }

    /**
     * @param array<int, string> $annotations
     *
     * @throws \Spryker\Glue\DocumentationGeneratorOpenApi\Exception\InvalidAnnotationFormatException
     *
     * @return array<int, mixed>
     */
    protected function transformAnnotationsToArray(array $annotations): array
    {
        $annotationsTransformed = [];
        foreach ($annotations as $annotation) {
            $annotation = trim(str_replace('*', '', $annotation));
            $annotationDecoded = $this->utilEncodingService->decodeJson($annotation, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidAnnotationFormatException(
                    sprintf(static::EXCEPTION_MESSAGE_INVALID_ANNOTATION_FORMAT, json_last_error_msg(), $annotation),
                );
            }
            $annotationsTransformed[] = $annotationDecoded;
        }

        return $annotationsTransformed;
    }

    /**
     * @param array<mixed> $annotationsParsed
     * @param array<mixed> $result
     *
     * @return array<mixed>
     */
    protected function getDataFromParsedAnnotations(array $annotationsParsed, array $result): array
    {
        foreach ($annotationsParsed as $annotationParsed) {
            foreach ($annotationParsed as $method => $values) {
                $result[$method] = $values;
            }
        }

        return $result;
    }
}
