<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use Generated\Shared\Transfer\PluginAnnotationsTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Exception\InvalidAnnotationFormatException;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\ResourceRelationshipPluginFinderInterface;
use Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface;

class ResourceRelationshipsPluginAnnotationAnalyzer implements ResourceRelationshipsPluginAnnotationAnalyzerInterface
{
    protected const PATTERN_REGEX_GLUE_ANNOTATION = '/(?<=@Glue\()(.|\n)*?(?=(\s\*\n)*?\))/';
    protected const EXCEPTION_MESSAGE_INVALID_ANNOTATION_FORMAT = 'Invalid JSON format: %s in %s';

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\ResourceRelationshipPluginFinderInterface
     */
    protected $resourceRelationshipPluginFinder;

    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Finder\ResourceRelationshipPluginFinderInterface $resourceRelationshipPluginFinder
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\Service\DocumentationGeneratorRestApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ResourceRelationshipPluginFinderInterface $resourceRelationshipPluginFinder,
        DocumentationGeneratorRestApiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->resourceRelationshipPluginFinder = $resourceRelationshipPluginFinder;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface $plugin
     *
     * @return \Generated\Shared\Transfer\PluginAnnotationsTransfer
     */
    public function getResourceAttributesFromResourceRelationshipPlugin(ResourceRelationshipPluginInterface $plugin): PluginAnnotationsTransfer
    {
        $gluePluginFiles = $this->resourceRelationshipPluginFinder->getPluginFilesFromPlugin($plugin);

        $pluginAnnotationsTransfer = new PluginAnnotationsTransfer();

        $parameters = [];
        foreach ($gluePluginFiles as $file) {
            $tokens = token_get_all(file_get_contents($file));
            $parameters[] = $this->parsePhpTokens($tokens);
        }

        if (!array_filter($parameters)) {
            return $pluginAnnotationsTransfer;
        }
        $pathAnnotationsTransfer = $pluginAnnotationsTransfer->fromArray(array_replace_recursive(...$parameters), true);

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
            $annotationsParsed = $this->getDocCommentParameters($phpToken[1]);
            if ($annotationsParsed) {
                $result = $this->getDataFromParsedAnnotations($annotationsParsed, $result);
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
            $annotationDecoded = json_decode($annotation, true);
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
