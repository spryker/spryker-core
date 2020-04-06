<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Generated\Shared\Transfer\PluginAnnotationsTransfer;
use ReflectionClass;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidAnnotationFormatException;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface;

class ResourceRelationshipsPluginAnnotationAnalyzer implements ResourceRelationshipsPluginAnnotationAnalyzerInterface
{
    /**
     * @uses \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzer::PATTERN_REGEX_GLUE_ANNOTATION
     */
    protected const PATTERN_REGEX_GLUE_ANNOTATION = '/(?<=@Glue\()(.|\n)*?(?=(\s\*\n)*?\))/';

    /**
     * @uses \Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer\GlueAnnotationAnalyzer::EXCEPTION_MESSAGE_INVALID_ANNOTATION_FORMAT
     */
    protected const EXCEPTION_MESSAGE_INVALID_ANNOTATION_FORMAT = 'Invalid JSON format: %s in %s';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(DocumentationGeneratorRestApiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $plugin
     *
     * @return \Generated\Shared\Transfer\PluginAnnotationsTransfer
     */
    public function getResourceAttributesFromResourceRelationshipPlugin(ResourceRelationshipPluginInterface $plugin): PluginAnnotationsTransfer
    {
        $pluginAnnotationsTransfer = new PluginAnnotationsTransfer();

        $classFileName = $this->getClassFileName($plugin);
        $parameters = $this->getParsedPhpTokens($classFileName);

        if (!array_filter($parameters)) {
            return $pluginAnnotationsTransfer;
        }

        return $pluginAnnotationsTransfer->fromArray(array_replace_recursive(...$parameters), true);
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $plugin
     *
     * @return string
     */
    protected function getClassFileName(ResourceRelationshipPluginInterface $plugin): string
    {
        $reflectionClass = new ReflectionClass(get_class($plugin));

        return $reflectionClass->getFileName();
    }

    /**
     * @param string $pluginFileName
     *
     * @return array
     */
    protected function getParsedPhpTokens(string $pluginFileName): array
    {
        $pluginContents = file_get_contents($pluginFileName);

        $tokens = token_get_all($pluginContents);

        return $this->parsePhpTokens($tokens);
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
            $annotationsParsed = $this->getDocCommentParameters($phpToken[1]);
            if ($annotationsParsed) {
                $result[] = $this->getDataFromParsedAnnotations($annotationsParsed, $result);
            }
        }

        return $result;
    }

    /**
     * @param string $comment
     *
     * @return array|null
     */
    protected function getDocCommentParameters(string $comment): ?array
    {
        if (!preg_match_all(static::PATTERN_REGEX_GLUE_ANNOTATION, $comment, $pregMatches)) {
            return null;
        }

        $matchesTrimmed = [];
        foreach ($pregMatches[0] as $item) {
            $matchesTrimmed[] = trim($item);
        }

        $matchesFiltered = array_filter($matchesTrimmed, function ($match) {
            return (bool)strlen($match);
        });
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
