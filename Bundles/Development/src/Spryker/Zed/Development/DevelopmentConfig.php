<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionOptionConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class DevelopmentConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getBundleDirectory()
    {
        return $this->getConfig()->get(ApplicationConstants::APPLICATION_SPRYKER_ROOT) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     */
    public function getPathToRoot()
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR;
    }

    /**
     * @deprecated use getBundleDirectory() to get the path to bundles
     *
     * @return string
     */
    public function getPathToSpryker()
    {
        return $this->getBundleDirectory();
    }

    /**
     * Either a relative or full path to the ruleset.xml or a name of an installed
     * standard (see `phpcs -i` for a list of available ones).
     *
     * @return string
     */
    public function getCodingStandard()
    {
        $vendorDir = APPLICATION_VENDOR_DIR . DIRECTORY_SEPARATOR;

        return $vendorDir . 'spryker/code-sniffer/Spryker/ruleset.xml';
    }

    /**
     * Either a relative or full path to the ruleset.xml or a name of an installed
     * standard. Can also be a comma separated list of multiple ones.
     *
     * @return string
     */
    public function getArchitectureStandard()
    {
        return __DIR__ . '/Business/PhpMd/ruleset.xml';
    }

    /**
     * @return string
     */
    public function getPathToComposerLock()
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'composer.lock';
    }

    /**
     * @return string
     */
    public function getPathToJsonDependencyTree()
    {
        $pathParts = [
            APPLICATION_ROOT_DIR,
            'data',
            'dependencyTree.json',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathParts);
    }

    /**
     * @return string
     */
    public function getPathToBundleConfig()
    {
        return __DIR__ . '/Business/DependencyTree/bundle_config.json';
    }

    /**
     * @return array
     */
    public function getExternalToInternalNamespaceMap()
    {
        return [
            'Psr\\' => 'spryker/log',
            'Propel\\' => 'spryker/propel',
            'Silex\\' => 'spryker/silex',
            'Pimple\\' => 'spryker/pimple',
            'Predis\\' => 'spryker/redis',
            'Guzzle\\' => 'spryker/guzzle',
            'GuzzleHttp\\' => 'spryker/guzzle',
            'League\\Csv\\' => 'spryker/csv',
            'Monolog\\' => 'spryker/monolog',
            'Elastica\\' => 'spryker/elastica',
            'Symfony\\Component\\' => 'spryker/symfony',
            'Twig_' => 'spryker/twig',
            'Zend\\' => 'spryker/zend',
            'phpDocumentor\\GraphViz\\' => 'spryker/graphviz',
        ];
    }

    /**
     * @return array
     */
    public function getExternalToInternalMap()
    {
        return [
            'psr/log' => 'spryker/log',
            'propel/propel' => 'spryker/propel',
            'silex/silex' => 'spryker/silex',
            'pimple/pimple' => 'spryker/pimple',
            'mandrill/mandrill' => 'spryker/mandrill',
            'predis/predis' => 'spryker/redis',
            'guzzle/guzzle' => 'spryker/guzzle',
            'guzzlehttp/guzzle' => 'spryker/guzzle',
            'league/csv' => 'spryker/csv',
            'monolog/monolog' => 'spryker/monolog',
            'ruflin/elastica' => 'spryker/elastica',
            '/symfony/' => 'spryker/symfony',
            'twig/twig' => 'spryker/twig',
            '/zendframework/' => 'spryker/zend',
            'phpdocumentor/graphviz' => 'spryker/graphviz',
        ];
    }

    /**
     * @return array
     */
    public function getIgnorableDependencies()
    {
        return [
            'codeception/codeception',
            'spryker/code-sniffer',
            'pdepend/pdepend',
            'phploc/phploc',
            'phpmd/phpmd',
            'sebastian/phpcpd',
            'codeception/codeception',
            'fabpot/php-cs-fixer',
            'sensiolabs/security-checker',
            'sllh/composer-versions-check',
        ];
    }

    /**
     * @return string[]
     */
    public function getYvesIdeAutoCompletionOptions()
    {
        $options = $this->getDefaultIdeAutoCompletionOptions();
        $options[IdeAutoCompletionOptionConstants::APPLICATION_NAME] = 'Yves';

        return $options;
    }

    /**
     * @return string[]
     */
    public function getZedIdeAutoCompletionOptions()
    {
        $options = $this->getDefaultIdeAutoCompletionOptions();
        $options[IdeAutoCompletionOptionConstants::APPLICATION_NAME] = 'Zed';

        return $options;
    }

    /**
     * @return array
     */
    public function getClientIdeAutoCompletionOptions()
    {
        $options = $this->getDefaultIdeAutoCompletionOptions();
        $options[IdeAutoCompletionOptionConstants::APPLICATION_NAME] = 'Client';

        return $options;
    }

    /**
     * @return array
     */
    public function getServiceIdeAutoCompletionOptions()
    {
        $options = $this->getDefaultIdeAutoCompletionOptions();
        $options[IdeAutoCompletionOptionConstants::APPLICATION_NAME] = 'Service';

        return $options;
    }

    /**
     * @return array
     */
    protected function getDefaultIdeAutoCompletionOptions()
    {
        return [
            IdeAutoCompletionOptionConstants::TARGET_BASE_DIRECTORY => APPLICATION_SOURCE_DIR . '/',
            IdeAutoCompletionOptionConstants::TARGET_DIRECTORY_PATTERN => sprintf(
                'Generated/%s/Ide',
                IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER
            ),
            IdeAutoCompletionOptionConstants::TARGET_NAMESPACE_PATTERN => sprintf(
                'Generated\%s\Ide',
                IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER
            ),
        ];
    }

    /**
     * @return string[]
     */
    public function getIdeAutoCompletionSourceDirectoryGlobPatterns()
    {
        return [
            $this->get(ApplicationConstants::APPLICATION_SPRYKER_ROOT) . '/*/src/' => 'Spryker/*/',
            APPLICATION_SOURCE_DIR . '/' => $this->get(ApplicationConstants::PROJECT_NAMESPACE) . '/*/',
        ];
    }

    /**
     * @return string[]
     */
    public function getIdeAutoCompletionGeneratorTemplatePaths()
    {
        return [
            __DIR__ . '/Business/IdeAutoCompletion/Generator/Templates',
        ];
    }

}
