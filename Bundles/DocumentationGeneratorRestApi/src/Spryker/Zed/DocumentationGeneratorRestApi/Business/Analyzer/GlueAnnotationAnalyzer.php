<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Generated\Shared\Transfer\RestApiDocumentationPathAnnotationsTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidAnnotationFormatException;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface;

/**
 * Specification
 *  - Parses a .php file and checks if it contains a PHPDock annotation in specific format of Glue annotations.
 *  - Glue annotation start with @Glue tag and all the parameters are written inside brackets in JSON format, e.g.:
 *  - @Glue("gerResource": {"summary": "Some endpoint summary"}}) will define summary for endpoint's GET method.
 *  - All parameters should start with defining the method they're related to.
 *  - Method could be one of the following:
 *      - getResource - GET method, that returns exactly one resource and doesn't contains id in path (e.g. /search);
 *      - getResourceById - GET method, that returns exactly one resource and should contain id in path (e.g. /wishlists/{wishlistId});
 *      - getCollection - GET method, that returns collection of resources (e.g. /wishlists);
 *      - post, patch, delete - respectively POST, PATCH and DELETE methods representation.
 *  - Method's parameters may consist of:
 *      - "summary" - method's summary - array of strings;
 *      - "headers" - additional headers, that can be passed with request - array of strings;
 *      - "responseClass" - defines FQCN of transfer, that represents response object - string;
 *      - "isEmptyResponse" - defines is endpoint doesn't have a response body data (e.g. /customer-forgotten-password)
 *      - "responses" - defines all possible error responses, in format "code": "message" - JSON object
 */
class GlueAnnotationAnalyzer implements GlueAnnotationAnalyzerInterface
{
    protected const PATTERN_REGEX_GLUE_ANNOTATION = '/(?<=@Glue\()(.|\n)*?(?=(\s\*\n)*?\))/';
    protected const EXCEPTION_MESSAGE_INVALID_ANNOTATION_FORMAT = 'Invalid JSON format: %s in %s';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface
     */
    protected $glueControllerFinder;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\GlueControllerFinderInterface $glueControllerFinder
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(GlueControllerFinderInterface $glueControllerFinder, DocumentationGeneratorRestApiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->glueControllerFinder = $glueControllerFinder;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathAnnotationsTransfer
     */
    public function getResourceParametersFromPlugin(ResourceRoutePluginInterface $plugin): RestApiDocumentationPathAnnotationsTransfer
    {
        $glueControllerFiles = $this->glueControllerFinder->getGlueControllerFilesFromPlugin($plugin);

        $pathAnnotationsTransfer = new RestApiDocumentationPathAnnotationsTransfer();
        $parameters = [];
        foreach ($glueControllerFiles as $file) {
            $tokens = token_get_all(file_get_contents($file));
            $parameters[] = $this->parsePhpTokens($tokens);
        }

        if (!$parameters) {
            return $pathAnnotationsTransfer;
        }
        $pathAnnotationsTransfer = $pathAnnotationsTransfer->fromArray(array_replace_recursive(...$parameters), true);

        return $pathAnnotationsTransfer;
    }

    /**
     * @param array $phpTokens
     *
     * @return array
     */
    protected function parsePhpTokens(array $phpTokens): array
    {
        $result = [];
        $phpTokens = array_filter($phpTokens, 'is_array');
        foreach ($phpTokens as $phpToken) {
            if ($phpToken[0] !== T_DOC_COMMENT) {
                continue;
            }
            $annotationsParsed = $this->getParametersFromDocComment($phpToken[1]);
            if ($annotationsParsed) {
                $result = $this->getDataFromAnnotationsParsed($annotationsParsed, $result);
            }
        }

        return $result;
    }

    /**
     * @param string $comment
     *
     * @return array|null
     */
    protected function getParametersFromDocComment(string $comment): ?array
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
     * @param array $annotations
     *
     * @throws \Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidAnnotationFormatException
     *
     * @return array
     */
    protected function transformAnnotationsToArray(array $annotations): array
    {
        $annotationsTransformed = [];
        foreach ($annotations as $annotation) {
            $annotation = trim(str_replace('*', '', $annotation));
            $annotationDecoded = $this->utilEncodingService->decodeJson($annotation, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidAnnotationFormatException(
                    sprintf(static::EXCEPTION_MESSAGE_INVALID_ANNOTATION_FORMAT, json_last_error_msg(), $annotation)
                );
            }
            $annotationsTransformed[] = $annotationDecoded;
        }

        return $annotationsTransformed;
    }

    /**
     * @param array $annotationsParsed
     * @param array $result
     *
     * @return array
     */
    protected function getDataFromAnnotationsParsed(array $annotationsParsed, array $result): array
    {
        foreach ($annotationsParsed as $annotationParsed) {
            foreach ($annotationParsed as $method => $values) {
                $result[$method] = $values;
            }
        }

        return $result;
    }
}
