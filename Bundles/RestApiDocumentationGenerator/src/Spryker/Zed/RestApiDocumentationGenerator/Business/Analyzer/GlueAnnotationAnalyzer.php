<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer;

use Generated\Shared\Transfer\RestApiDocumentationPathAnnotationsTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\InvalidAnnotationFormatException;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Dependency\Service\RestApiDocumentationGeneratorToUtilEncodingServiceInterface;

class GlueAnnotationAnalyzer implements GlueAnnotationAnalyzerInterface
{
    protected const PATTERN_REGEX_GLUE_ANNOTATION = '/(?<=@Glue\()(.|\n)*?(?=(\s\*\n)*?\))/';
    protected const EXCEPTION_MESSAGE_INVALID_ANNOTATION_FORMAT = 'Invalid JSON format';

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface
     */
    protected $glueControllerFinder;

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Dependency\Service\RestApiDocumentationGeneratorToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface $glueControllerFinder
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Dependency\Service\RestApiDocumentationGeneratorToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(GlueControllerFinderInterface $glueControllerFinder, RestApiDocumentationGeneratorToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->glueControllerFinder = $glueControllerFinder;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\InvalidAnnotationFormatException
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathAnnotationsTransfer
     */
    public function getResourceParametersFromPlugin(ResourceRoutePluginInterface $plugin): RestApiDocumentationPathAnnotationsTransfer
    {
        $glueControllerFiles = $this->glueControllerFinder->getGlueControllerFilesFromPlugin($plugin);

        $pathAnnotations = new RestApiDocumentationPathAnnotationsTransfer();
        $parameters = [];
        foreach ($glueControllerFiles as $file) {
            $tokens = token_get_all(file_get_contents($file));
            try {
                $parameters[] = $this->parsePhpTokens($tokens);
            } catch (InvalidAnnotationFormatException $e) {
                throw new InvalidAnnotationFormatException('Invalid annotations format in ' . $file->getPathname());
            }
        }

        if (!$parameters) {
            return $pathAnnotations;
        }
        $pathAnnotations = $pathAnnotations->fromArray(array_replace_recursive(...$parameters), true);

        return $pathAnnotations;
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
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\InvalidAnnotationFormatException
     *
     * @return array
     */
    protected function transformAnnotationsToArray(array $annotations): array
    {
        foreach ($annotations as &$annotation) {
            $annotation = trim(str_replace('*', '', $annotation));
            $annotation = $this->utilEncodingService->decodeJson($annotation, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidAnnotationFormatException(json_last_error_msg());
            }
        }

        return $annotations;
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
