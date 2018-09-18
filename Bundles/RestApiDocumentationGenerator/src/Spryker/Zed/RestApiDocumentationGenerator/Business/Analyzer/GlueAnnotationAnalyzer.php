<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Analyzer;

use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\AnnotationException;

class GlueAnnotationAnalyzer implements GlueAnnotationAnalyzerInterface
{
    protected const REGEX_PATTERN_GLUE_ANNOTATION = '/(?<=@Glue\(\n)(.|\n)*?(?=(\s\*\n)*?\))/';
    protected const REGEX_PATTERN_TRIM_SYMBOLS = '/[^(\w\s)=,\.\n]*/';
    protected const REGEX_PATTERN_ACTION_NAME = '/(\w)+Action/';

    protected const NAMESPACE_PATTERN_CONTROLLER = '%s\Controller\%s';

    protected const ERROR_MESSAGE_COMMA_MISSED_PATTERN = 'Line must end with "," in multiline annotation. Found in  %s';

    protected const FINDER_PATTERN_PLACEHOLDER_MODULE = '%module%';
    protected const FINDER_PATTERN_PLACEHOLDER_CONTROLLER = '%controller%';
    protected const FINDER_PATTERN_PROJECT_CONTROLLER = APPLICATION_SOURCE_DIR . '/' . self::FINDER_PATTERN_PLACEHOLDER_MODULE . '/Glue/' . self::FINDER_PATTERN_PLACEHOLDER_MODULE . '/Controller/' . self::FINDER_PATTERN_PLACEHOLDER_CONTROLLER . '.php';
    protected const FINDER_PATTERN_PRODUCT_CONTROLLER = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/' . self::FINDER_PATTERN_PLACEHOLDER_MODULE . '/src/Spryker/Glue/' . self::FINDER_PATTERN_PLACEHOLDER_MODULE . '/Controller/' . self::FINDER_PATTERN_PLACEHOLDER_CONTROLLER . '.php';

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
            static::NAMESPACE_PATTERN_CONTROLLER,
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
                [static::FINDER_PATTERN_PLACEHOLDER_MODULE, static::FINDER_PATTERN_PLACEHOLDER_CONTROLLER],
                [$module, $controller],
                static::FINDER_PATTERN_PRODUCT_CONTROLLER
            ),
            str_replace(
                [static::FINDER_PATTERN_PLACEHOLDER_MODULE, static::FINDER_PATTERN_PLACEHOLDER_CONTROLLER],
                [$module, $controller],
                static::FINDER_PATTERN_PROJECT_CONTROLLER
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
            if ($token[0] === T_STRING && preg_match(static::REGEX_PATTERN_ACTION_NAME, $token[1]) && $lastParsedParameters) {
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
        preg_match(static::REGEX_PATTERN_GLUE_ANNOTATION, $comment, $matches);
        $matches = array_map('trim', $matches);
        $matchesFiltered = array_filter($matches, 'strlen');
        if (!$matchesFiltered) {
            return null;
        }

        array_walk($matchesFiltered, function (&$match) {
            $match = trim(preg_replace(static::REGEX_PATTERN_TRIM_SYMBOLS, '', $match));
        });

        foreach ($matchesFiltered as $matchFiltered) {
            $matchesExploded = explode(PHP_EOL, $matchFiltered);
            $this->validateMultilineAnnotations($matchesExploded);

            foreach ($matchesExploded as $match) {
                [$parameterName, $parameterValue] = explode('=', $match);
                $parameterValue = $this->filterParameter($parameterValue);
                $parameters[$parameterName] = $parameterValue;
            }
        }

        return $parameters;
    }

    /**
     * @param array $annotationsExploded
     *
     * @throws \Spryker\Zed\RestApiDocumentationGenerator\Business\Exception\AnnotationException
     *
     * @return bool
     */
    protected function validateMultilineAnnotations(array &$annotationsExploded): bool
    {
        if (count($annotationsExploded) <= 1) {
            return true;
        }

        foreach ($annotationsExploded as $key => &$value) {
            if (substr($value, -1) !== ',' && $key !== count($annotationsExploded) - 1) {
                throw new AnnotationException(sprintf(static::ERROR_MESSAGE_COMMA_MISSED_PATTERN, $value));
            }
            $value = str_replace(',', '', $value);
        }
        unset($value);

        return true;
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

        return $parameter;
    }
}
