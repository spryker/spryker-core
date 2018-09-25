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

    protected const PATTERN_ERROR_MESSAGE_COMMA_MISSED = 'Line must end with "," in multiline annotation. Found in  %s';

    /**
     * @var \Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\RestApiDocumentationGenerator\Business\Finder\GlueControllerFinderInterface $finder
     */
    public function __construct(GlueControllerFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return \Generated\Shared\Transfer\RestApiDocumentationPathAnnotationsTransfer
     */
    public function getParametersFromPlugin(ResourceRoutePluginInterface $plugin): RestApiDocumentationPathAnnotationsTransfer
    {
        $controllerFiles = $this->finder->getGlueControllerFilesFromPlugin($plugin);

        $pathAnnotations = new RestApiDocumentationPathAnnotationsTransfer();
        $parameters = [];
        foreach ($controllerFiles as $file) {
            $tokens = token_get_all(file_get_contents($file));
            $parameters[] = $this->parseTokens($tokens);
        }

        if (!$parameters) {
            return $pathAnnotations;
        }
        $pathAnnotations = $pathAnnotations->fromArray(array_replace_recursive(...$parameters), true);

        return $pathAnnotations;
    }

    /**
     * @param array $tokens
     *
     * @return array
     */
    protected function parseTokens(array $tokens): array
    {
        $result = [];
        $lastParsedParameters = [];
        $tokens = array_filter($tokens, 'is_array');
        foreach ($tokens as $token) {
            if ($token[0] === T_DOC_COMMENT) {
                $lastParsedParameters = $this->getParametersFromDocComment($token[1]);
                continue;
            }
            if ($lastParsedParameters && $token[0] === T_STRING && preg_match(static::PATTERN_REGEX_ACTION_NAME, $token[1])) {
                $result[strtolower(str_replace('Action', '', $token[1]))] = $lastParsedParameters;
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
        $parameters = [];
        preg_match_all(static::PATTERN_REGEX_GLUE_ANNOTATION, $comment, $matches);
        $matches = array_map('trim', $matches[0]);
        $matchesFiltered = array_filter($matches, 'strlen');
        if (!$matchesFiltered) {
            return null;
        }

        array_walk($matchesFiltered, function (&$match) {
            $match = preg_replace(static::PATTERN_REGEX_TRIM_SYMBOLS, '', $match);
            $match = preg_replace(static::PATTERN_REGEX_TRIM_MULTI_SPACES, '', $match);
            $match = trim($match);
            if (preg_match_all(static::PATTERN_REGEX_MULTI_LINE_ANNOTATION, $match, $matches, PREG_OFFSET_CAPTURE)) {
                $match = $this->convertMultiLineAnnotationToSingleLine($match, $matches[0]);
            }
        });

        $parameters += $this->getParametersFromAnnotations($matchesFiltered);

        return $parameters;
    }

    /**
     * @param array $annotationsExploded
     *
     * @return void
     */
    protected function filterMultiLineAnnotations(array &$annotationsExploded): void
    {
        if (count($annotationsExploded) <= 1) {
            return;
        }

        foreach ($annotationsExploded as &$value) {
            $value = trim($value, ", \t\n\r\0\x0B");
        }
        unset($value);
    }

    /**
     * @param string $parameter
     *
     * @return bool|int|float|string
     */
    protected function filterParameter(string $parameter)
    {
        if (filter_var($parameter, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) {
            return filter_var($parameter, FILTER_VALIDATE_BOOLEAN);
        }

        if (filter_var($parameter, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE)) {
            return filter_var($parameter, FILTER_VALIDATE_INT);
        }

        if (filter_var($parameter, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE)) {
            return filter_var($parameter, FILTER_VALIDATE_FLOAT);
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
     * @param array $annotations
     *
     * @return array
     */
    protected function getParametersFromAnnotations(array $annotations): array
    {
        $parameters = [];
        foreach ($annotations as $annotation) {
            $annotationExploded = explode(PHP_EOL, $annotation);
            $this->filterMultiLineAnnotations($annotationExploded);

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
