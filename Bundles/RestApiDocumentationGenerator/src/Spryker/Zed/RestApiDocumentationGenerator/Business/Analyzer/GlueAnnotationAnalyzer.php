<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;

class GlueAnnotationAnalyzer implements GlueAnnotationAnalyzerInterface
{
    protected const PATTERN_REGEX_GLUE_ANNOTATION = '/(?<=@Glue\(\n)(.|\n)*?(?=(\s\*\n)*?\))/';
    protected const PATTERN_REGEX_TRIM_SYMBOLS = '/[^(\w\s),{,},\-,=,\.\n]*/';
    protected const PATTERN_REGEX_TRIM_MULTI_SPACES = '/ {2,}/';
    protected const PATTERN_REGEX_MULTI_LINE_ANNOTATION = '/(?<=\w=\{\n)(.|\n)+?(?=\n\})/';
    protected const PATTERN_REGEX_ACTION_NAME = '/(\w)+Action/';

    protected const PATTERN_NAMESPACE_CONTROLLER = '%s\Controller\%s';

    protected const PATTERN_ERROR_MESSAGE_COMMA_MISSED = 'Line must end with "," in multiline annotation. Found in  %s';

    protected const PATTERN_FINDER_PLACEHOLDER_MODULE = '%module%';
    protected const PATTERN_FINDER_PLACEHOLDER_CONTROLLER = '%controller%';
    protected const PATTERN_FINDER_PROJECT_CONTROLLER = APPLICATION_SOURCE_DIR . '/' . self::PATTERN_FINDER_PLACEHOLDER_MODULE . '/Glue/' . self::PATTERN_FINDER_PLACEHOLDER_MODULE . '/Controller/' . self::PATTERN_FINDER_PLACEHOLDER_CONTROLLER . '.php';
    protected const PATTERN_FINDER_PRODUCT_CONTROLLER = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . self::PATTERN_FINDER_PLACEHOLDER_MODULE . '/src/Spryker/Glue/' . self::PATTERN_FINDER_PLACEHOLDER_MODULE . '/Controller/' . self::PATTERN_FINDER_PLACEHOLDER_CONTROLLER . '.php';

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return array
     */
    public function getParametersFromPlugin(ResourceRoutePluginInterface $plugin): array
    {
        $controller = $this->getPluginControllerClass($plugin);
        $files = $this->getControllerSourceDirs($controller);

        $parameters = [];
        foreach ($files as $file) {
            if (file_exists($file)) {
                $tokens = token_get_all(file_get_contents($file));
                $parameters = $this->parseTokens($tokens);
            }
        }

        return $parameters;
    }

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface $plugin
     *
     * @return string
     */
    protected function getPluginControllerClass(ResourceRoutePluginInterface $plugin): string
    {
        $controllerClass = implode('', array_map('ucfirst', explode('-', $plugin->getController()))) . 'Controller';
        $pluginClass = get_class($plugin);
        $substr = substr($pluginClass, 0, strpos($pluginClass, '\Plugin\\'));

        return sprintf(
            static::PATTERN_NAMESPACE_CONTROLLER,
            $substr,
            $controllerClass
        );
    }

    /**
     * @param string $controllerClass
     *
     * @return array
     */
    protected function getControllerSourceDirs(string $controllerClass): array
    {
        $controllerClassExploded = explode('\\', $controllerClass);

        $controller = array_slice($controllerClassExploded, -1)[0];
        $module = array_slice($controllerClassExploded, -3)[0];

        return [
            str_replace(
                [static::PATTERN_FINDER_PLACEHOLDER_MODULE, static::PATTERN_FINDER_PLACEHOLDER_CONTROLLER],
                [$module, $controller],
                static::PATTERN_FINDER_PRODUCT_CONTROLLER
            ),
            str_replace(
                [static::PATTERN_FINDER_PLACEHOLDER_MODULE, static::PATTERN_FINDER_PLACEHOLDER_CONTROLLER],
                [$module, $controller],
                static::PATTERN_FINDER_PROJECT_CONTROLLER
            ),
        ];
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
            if ($token[0] === T_STRING && preg_match(static::PATTERN_REGEX_ACTION_NAME, $token[1]) && $lastParsedParameters) {
                $result[strtoupper(str_replace('Action', '', $token[1]))] = $lastParsedParameters;
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
                $parameters[$parameterName] = [$code => $this->filterParameter($description)];
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
