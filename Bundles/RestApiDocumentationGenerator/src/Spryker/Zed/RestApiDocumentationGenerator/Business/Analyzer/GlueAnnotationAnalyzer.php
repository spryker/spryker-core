<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer;

use Generated\Shared\Transfer\RestApiDocumentationPathAnnotationsTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface;

class GlueAnnotationAnalyzer implements GlueAnnotationAnalyzerInterface
{
    protected const PATTERN_REGEX_GLUE_ANNOTATION = '/(?<=@Glue\(\n)(.|\n)*?(?=(\s\*\n)*?\))/';
    protected const PATTERN_REGEX_TRIM_SYMBOLS = '/[^(\w\s),{,},\-,=,\.\n]*/';
    protected const PATTERN_REGEX_TRIM_MULTI_SPACES = '/ {2,}/';
    protected const PATTERN_REGEX_MULTI_LINE_ANNOTATION = '/(?<=\w=\{\n)(.|\n)+?(?=\n\})/';
    protected const PATTERN_REGEX_ACTION_NAME = '/(\w)+Action/';

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface
     */
    protected $glueControllerFinder;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface $glueControllerFinder
     */
    public function __construct(GlueControllerFinderInterface $glueControllerFinder)
    {
        $this->glueControllerFinder = $glueControllerFinder;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
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
            $parameters[] = $this->parsePhpTokens($tokens);
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
        $lastParsedParameters = [];
        $phpTokens = array_filter($phpTokens, 'is_array');
        foreach ($phpTokens as $phpToken) {
            if ($phpToken[0] === T_DOC_COMMENT) {
                $lastParsedParameters = $this->getParametersFromDocComment($phpToken[1]);
                continue;
            }
            if ($lastParsedParameters && $phpToken[0] === T_STRING && preg_match(static::PATTERN_REGEX_ACTION_NAME, $phpToken[1])) {
                $result[strtolower(str_replace('Action', '', $phpToken[1]))] = $lastParsedParameters;
                $lastParsedParameters = [];
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

        array_walk($matchesFiltered, $this->getGlueAnnotationFilter());

        return $this->getResourceParametersFromAnnotations($matchesFiltered);
    }

    /**
     * @param array $annotations
     *
     * @return array
     */
    protected function getResourceParametersFromAnnotations(array $annotations): array
    {
        $parameters = [];
        foreach ($annotations as $annotation) {
            $annotationExploded = explode(PHP_EOL, $annotation);
            $annotationExploded = $this->filterMultiLineAnnotations($annotationExploded);

            foreach ($annotationExploded as $annotationParameter) {
                if (preg_match('/\w*={.*}/', $annotationParameter)) {
                    $parameters += $this->getArrayParameterFromAnnotation($annotationParameter);
                    continue;
                }

                [$parameterName, $parameterValue] = explode('=', $annotationParameter);
                $parameterValue = $this->filterParameter($parameterValue);
                $parameters[$parameterName] = $parameterValue;
            }
        }

        return $parameters;
    }

    /**
     * @return callable
     */
    protected function getGlueAnnotationFilter(): callable
    {
        return function (&$match) {
            $match = preg_replace(static::PATTERN_REGEX_TRIM_SYMBOLS, '', $match);
            $match = preg_replace(static::PATTERN_REGEX_TRIM_MULTI_SPACES, '', $match);
            $match = trim($match);
            if (preg_match_all(static::PATTERN_REGEX_MULTI_LINE_ANNOTATION, $match, $matches, PREG_OFFSET_CAPTURE)) {
                $match = $this->convertMultiLineAnnotationToSingleLine($match, $matches[0]);
            }
        };
    }

    /**
     * @param array $annotationsExploded
     *
     * @return array
     */
    protected function filterMultiLineAnnotations(array $annotationsExploded): array
    {
        if (count($annotationsExploded) <= 1) {
            return $annotationsExploded;
        }

        return array_map(function ($annotation) {
            return trim($annotation, ", \t\n\r\0\x0B");
        }, $annotationsExploded);
    }

    /**
     * @param string $parameter
     *
     * @return mixed
     */
    protected function filterParameter(string $parameter)
    {
        if (filter_var($parameter, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE)) {
            return filter_var($parameter, FILTER_VALIDATE_INT);
        }

        if (filter_var($parameter, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE)) {
            return filter_var($parameter, FILTER_VALIDATE_FLOAT);
        }

        if (filter_var($parameter, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) {
            return filter_var($parameter, FILTER_VALIDATE_BOOLEAN);
        }

        return trim($parameter, ", \t\n\r\0\x0B");
    }

    /**
     * @param string $string
     * @param string[] $matches
     *
     * @return string
     */
    protected function convertMultiLineAnnotationToSingleLine(string $string, array $matches): string
    {
        foreach ($matches as $key => $match) {
            $replacement = implode('|', explode(PHP_EOL, $match[0]));
            $position = (int)$match[1] - strlen(PHP_EOL) * (2 * $key + 1);
            $length = strlen($match[0]) + 2 * strlen(PHP_EOL);
            $string = substr_replace($string, $replacement, $position, $length);
        }

        return $string;
    }

    /**
     * @param string $annotationParameter
     *
     * @return array
     */
    protected function getArrayParameterFromAnnotation(string $annotationParameter): array
    {
        $parameters = [];
        [$parameterName, $parameterValues] = preg_split('/(?<=\w)*(=)(?=\{)/', $annotationParameter);
        $parameterValues = trim($parameterValues, '{}|,');

        $parameterValues = explode('|', $parameterValues);
        foreach ($parameterValues as $parameterValue) {
            if (preg_match('/\d*=./', $parameterValue)) {
                [$code, $description] = explode('=', $parameterValue);
                $parameters[$parameterName][$code] = $this->filterParameter($description);
                continue;
            }
            $parameterValue = $this->filterParameter($parameterValue);
            if (!isset($parameters[$parameterName]) || !in_array($parameterValue, $parameters[$parameterName], true)) {
                $parameters[$parameterName][] = $parameterValue;
            }
        }

        return $parameters;
    }
}
