<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development;

use Spryker\Shared\Development\DevelopmentConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionConstants;
use Spryker\Zed\Development\Business\IdeAutoCompletion\IdeAutoCompletionOptionConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class DevelopmentConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const BUNDLE_PLACEHOLDER = '[BUNDLE]';

    /**
     * @var string
     */
    protected const PHPSTAN_CONFIG_FILENAME = 'phpstan.neon';

    /**
     * @var string
     */
    protected const NAMESPACE_SPRYKER = 'Spryker';

    /**
     * @var string
     */
    protected const NAMESPACE_SPRYKER_SHOP = 'SprykerShop';

    /**
     * @var string
     */
    protected const NAMESPACE_SPRYKER_ECO = 'SprykerEco';

    /**
     * @var string
     */
    protected const NAMESPACE_SPRYKER_SDK = 'SprykerSdk';

    /**
     * @var string
     */
    protected const NAMESPACE_SPRYKER_MIDDLEWARE = 'SprykerMiddleware';

    /**
     * @var string
     */
    protected const NAMESPACE_SPRYKER_MERCHANT_PORTAL = 'SprykerMerchantPortal';

    /**
     * @var string
     */
    protected const GROUP_SPRYKER_TEST = 'SprykerTest';

    /**
     * @var string
     */
    protected const NAMESPACE_SPRYKER_FEATURE = 'SprykerFeature';

    /**
     * @var array<string>
     */
    public const APPLICATION_NAMESPACES = [
        'Orm',
    ];

    /**
     * @var array<string>
     */
    public const APPLICATIONS = [
        'Client',
        'Service',
        'Shared',
        'Yves',
        'Zed',
        'Glue',
    ];

    /**
     * @var array
     */
    protected const APPLICATION_LAYERS = [
        'Zed',
        'Client',
        'Yves',
        'Glue',
        'Service',
        'Shared',
    ];

    /**
     * @var array<string>
     */
    protected const INTERNAL_NAMESPACES_LIST = [
        self::NAMESPACE_SPRYKER,
        self::NAMESPACE_SPRYKER_FEATURE,
        self::NAMESPACE_SPRYKER_SHOP,
        self::NAMESPACE_SPRYKER_MERCHANT_PORTAL,
    ];

    /**
     * @var array<string, string>
     */
    protected const INTERNAL_NAMESPACES_TO_PATH_MAPPING = [
        self::NAMESPACE_SPRYKER => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker/',
        self::NAMESPACE_SPRYKER_FEATURE => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker-feature/',
        self::NAMESPACE_SPRYKER_SHOP => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker-shop/',
        self::NAMESPACE_SPRYKER_ECO => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker-eco/',
        self::NAMESPACE_SPRYKER_SDK => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker-sdk/',
        self::NAMESPACE_SPRYKER_MIDDLEWARE => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker-middleware/',
        self::NAMESPACE_SPRYKER_MERCHANT_PORTAL => APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/spryker-merchant-portal/',
    ];

    /**
     * @var array<string>
     */
    protected const INTERNAL_PACKAGE_DIRECTORIES = ['spryker', 'spryker-shop', 'spryker-merchant-portal'];

    /**
     * @var int
     */
    protected const TIMEOUT_DEFAULT = 9000;

    /**
     * @api
     *
     * @return int
     */
    public function getPermissionMode(): int
    {
        return $this->get(DevelopmentConstants::DIRECTORY_PERMISSION, 0777);
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getInternalNamespaces(): array
    {
        return ['Spryker', 'SprykerFeature', 'SprykerEco', 'SprykerSdk', 'SprykerShop', 'Orm'];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getTwigPathPatterns(): array
    {
        return [
            $this->getPathToCore() . '%1$s/src/Spryker/Zed/%1$s/Presentation/',
            $this->getPathToCore() . '%1$s/src/Spryker/Yves/%1$s/Theme/',
            $this->getPathToShop() . '%1$s/src/SprykerShop/Yves/%1$s/Theme/',
            $this->getPathToCore() . '%1$s/src/SprykerFeature/Zed/%1$s/Presentation/',
            $this->getPathToCore() . '%1$s/src/SprykerFeature/Yves/%1$s/Theme/',
        ];
    }

    /**
     * Gets path to application root directory.
     *
     * @api
     *
     * @return string
     */
    public function getPathToRoot()
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR;
    }

    /**
     * Gets Application layers.
     *
     * @api
     *
     * @return array<string>
     */
    public function getApplications()
    {
        return static::APPLICATIONS;
    }

    /**
     * Gets Application namespaces.
     *
     * @api
     *
     * @return array<string>
     */
    public function getApplicationNamespaces()
    {
        return static::APPLICATION_NAMESPACES;
    }

    /**
     * Specification:
     * - Gets Application layers.
     *
     * @api
     *
     * @return array<string>
     */
    public function getApplicationLayers(): array
    {
        return static::APPLICATION_LAYERS;
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Development\Business\Module\PathBuilder\SprykerModulePathBuilder::buildPaths()} instead.
     *
     * Gets path to Spryker core modules.
     *
     * @return string
     */
    public function getPathToCore()
    {
        // Check for deprecated environment config constant.
        $path = $this->getConfig()->get(KernelConstants::SPRYKER_ROOT);
        if ($path) {
            return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }

        return $this->getPathToRoot() . 'vendor/spryker/';
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder\SprykerSdkPathBuilder::buildPaths()} instead.
     *
     * Gets path to SprykerSdk core modules.
     *
     * @return string
     */
    public function getPathToSdk()
    {
        return $this->getPathToRoot() . 'vendor/spryker-sdk/';
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Development\Business\Module\PathBuilder\SprykerShopModulePathBuilder::buildPaths()} instead.
     *
     * Gets path to SprykerShop core modules.
     *
     * @return string
     */
    public function getPathToShop()
    {
        return $this->getPathToRoot() . 'vendor/spryker-shop/';
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Development\Business\Module\PathBuilder\SprykerShopModulePathBuilder::buildPaths()} instead.
     *
     * Gets path to SprykerFeature core modules.
     *
     * @return string
     */
    public function getPathToFeature()
    {
        return $this->getPathToRoot() . 'vendor/spryker-feature/';
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Development\Business\Module\PathBuilder\SprykerEcoModulePathBuilder::buildPaths()} instead.
     *
     * Gets path to SprykerEco core modules.
     *
     * @return string
     */
    public function getPathToEco()
    {
        return $this->getPathToRoot() . 'vendor/spryker-eco/';
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getOrganizationPathMap(): array
    {
        return [
            'Spryker' => $this->getPathToCore(),
            'SprykerFeature' => $this->getPathToCore(),
            'SprykerEco' => $this->getPathToEco(),
        ];
    }

    /**
     * Either a relative or full path to the ruleset.xml or a name of an installed
     * standard (see `phpcs -i` for a list of available ones).
     *
     * Deprecated: Directly provide a ROOT phpcs.xml in your project instead.
     *
     * @api
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
     * @api
     *
     * @return string
     */
    public function getArchitectureStandard()
    {
        return dirname(__DIR__, 4) . '/resources/phpmd/ruleset.xml';
    }

    /**
     * Gets path to Application's composer.lock file.
     *
     * @api
     *
     * @return string
     */
    public function getPathToComposerLock()
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'composer.lock';
    }

    /**
     * @api
     *
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
     * @api
     *
     * @return string
     */
    public function getPhpstanConfigFilename(): string
    {
        return static::PHPSTAN_CONFIG_FILENAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPathToPhpstanModuleTemporaryConfigFolder()
    {
        return APPLICATION_ROOT_DIR . '/data/phpstan/';
    }

    /**
     * Gets path to module config that holds information about engine modules.
     *
     * @api
     *
     * @return string
     */
    public function getPathToBundleConfig()
    {
        return __DIR__ . '/Business/DependencyTree/bundle_config.json';
    }

    /**
     * @api
     *
     * @return array<string, string>
     */
    public function getExternalToInternalNamespaceMap()
    {
        return [
            'Codeception\\' => 'spryker/testify',
            'Doctrine\\Common\\Inflector' => 'spryker/doctrine-inflector',
            'Doctrine\\Inflector\\InflectorFactory' => 'spryker/doctrine-inflector',
            'DMS\\PHPUnitExtensions\\' => 'spryker/testify',
            'Egulias\\EmailValidator\\' => 'spryker/egulias',
            'Elastica\\' => 'spryker/elastica',
            'Faker\\' => 'spryker/testify',
            'Guzzle\\' => 'spryker/guzzle',
            'GuzzleHttp\\' => 'spryker/guzzle',
            'JsonPath\\' => 'spryker/json-path',
            'JsonSchema\\' => 'spryker/json-schema',
            'Laminas\\Config' => 'spryker/laminas',
            'Laminas\\Filter' => 'spryker/laminas',
            'Laminas\\ServiceManager' => 'spryker/laminas',
            'League\\Csv\\' => 'spryker/csv',
            'Monolog\\' => 'spryker/monolog',
            'org\\bovigo\\vfs\\' => 'spryker/testify',
            'phpDocumentor\\GraphViz\\' => 'spryker/graphviz',
            'Propel\\' => 'spryker/propel-orm',
            'PHPUnit\\' => 'spryker/testify',
            'Pimple' => 'spryker/container',
            'Predis\\' => 'spryker/redis',
            'Psr\\Log\\' => 'spryker/log',
            'Psr\\Container\\' => 'spryker/container',
            'Ramsey\\Uuid' => 'spryker/ramsey-uuid',
            'Silex\\' => 'spryker/silex',
            'Spryker\\DecimalObject\\' => 'spryker/decimal-object',
            'Spryker\\ChecksumGenerator\\' => 'spryker/checksum-generator',
            'Symfony\\Bridge\\Twig\\' => 'spryker/symfony',
            'Symfony\\Bundle\\WebProfilerBundle\\' => 'spryker/symfony',
            'Symfony\\Component\\' => 'spryker/symfony',
            'Symfony\\Contracts\\' => 'spryker/symfony',
            'Symfony\\Cmf\\' => 'spryker/symfony',
            'Twig_' => 'spryker/twig',
            'Twig\\' => 'spryker/twig',
            'Webmozart\\Glob' => 'spryker/util-glob',
            'Willdurand\\Negotiation\\' => 'spryker/willdurand-negotiation',
            'Zend\\' => 'spryker/zend',
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getExternalToInternalMap()
    {
        return [
            'codeception/codeception' => 'spryker/testify',
            'doctrine/inflector' => 'spryker/doctrine-inflector',
            'egulias/email-validator' => 'spryker/egulias',
            'fakerphp/faker' => 'spryker/testify',
            'galbar/jsonpath' => 'spryker/json-path',
            'guzzlehttp/guzzle' => 'spryker/guzzle',
            'justinrainbow/json-schema' => 'spryker/json-schema',
            'laminas/laminas-config' => 'spryker/laminas',
            'laminas/laminas-filter' => 'spryker/laminas',
            'laminas/laminas-servicemanager' => 'spryker/laminas',
            'league/csv' => 'spryker/csv',
            'league/oauth2-server' => 'spryker/oauth',
            'mandrill/mandrill' => 'spryker/mandrill',
            'mikey179/vfsstream' => 'spryker/testify',
            'minishlink/web-push' => 'spryker/push-notification-web-push-php',
            'moneyphp/money' => 'spryker/money',
            'monolog/monolog' => 'spryker/monolog',
            'pimple/pimple' => 'spryker/container',
            'phpbench/phpbench' => 'spryker-sdk/benchmark',
            'phpdocumentor/graphviz' => 'spryker/graphviz',
            'predis/predis' => 'spryker/redis',
            'propel/propel' => 'spryker/propel-orm',
            'psr/log' => 'spryker/log',
            'psr/container' => 'spryker/container',
            'ramsey/uuid' => 'spryker/ramsey-uuid',
            'ruflin/elastica' => 'spryker/elastica',
            'symfony-cmf/routing' => 'spryker/symfony',
            'symfony/config' => 'spryker/symfony',
            'symfony/console' => 'spryker/symfony',
            'symfony/debug' => 'spryker/symfony',
            'symfony/error-handler' => 'spryker/symfony',
            'symfony/event-dispatcher' => 'spryker/symfony',
            'symfony/filesystem' => 'spryker/symfony',
            'symfony/finder' => 'spryker/symfony',
            'symfony/form' => 'spryker/symfony',
            'symfony/http-client' => 'spryker/symfony',
            'symfony/http-foundation' => 'spryker/symfony',
            'symfony/http-kernel' => 'spryker/symfony',
            'symfony/intl' => 'spryker/symfony',
            'symfony/mime' => 'spryker/symfony',
            'symfony/messenger' => 'spryker/symfony',
            'symfony/lock' => 'spryker/symfony',
            'symfony/options-resolver' => 'spryker/symfony',
            'symfony/process' => 'spryker/symfony',
            'symfony/property-access' => 'spryker/symfony',
            'symfony/routing' => 'spryker/symfony',
            'symfony/security-core' => 'spryker/symfony',
            'symfony/security-csrf' => 'spryker/symfony',
            'symfony/security-guard' => 'spryker/symfony',
            'symfony/security-http' => 'spryker/symfony',
            'symfony/serializer' => 'spryker/symfony',
            'symfony/stopwatch' => 'spryker/symfony',
            'symfony/translation' => 'spryker/symfony',
            'symfony/translation-contracts' => 'spryker/symfony',
            'symfony/twig-bridge' => 'spryker/symfony',
            'symfony/validator' => 'spryker/symfony',
            'symfony/web-profiler-bundle' => 'spryker/web-profiler',
            'symfony/yaml' => 'spryker/symfony',
            'swiftmailer/swiftmailer' => 'spryker/mail',
            'twig/twig' => 'spryker/twig',
            'webmozart/glob' => 'spryker/util-glob',
            'willdurand/negotiation' => 'spryker/willdurand-negotiation',
            'zendframework/zend-config' => 'spryker/zend',
            'zendframework/zend-filter' => 'spryker/zend',
            'zendframework/zend-servicemanager' => 'spryker/zend',
        ];
    }

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return array<string>
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
            'fabpot/php-cs-fixer',
            'sensiolabs/security-checker',
            'sllh/composer-versions-check',
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getYvesIdeAutoCompletionOptions()
    {
        $options = $this->getDefaultIdeAutoCompletionOptions();
        $options[IdeAutoCompletionOptionConstants::APPLICATION_NAME] = 'Yves';

        return $options;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getZedIdeAutoCompletionOptions()
    {
        $options = $this->getDefaultIdeAutoCompletionOptions();
        $options[IdeAutoCompletionOptionConstants::APPLICATION_NAME] = 'Zed';

        return $options;
    }

    /**
     * @api
     *
     * @return array<string, mixed>
     */
    public function getClientIdeAutoCompletionOptions()
    {
        $options = $this->getDefaultIdeAutoCompletionOptions();
        $options[IdeAutoCompletionOptionConstants::APPLICATION_NAME] = 'Client';

        return $options;
    }

    /**
     * @api
     *
     * @return array<string, mixed>
     */
    public function getGlueIdeAutoCompletionOptions()
    {
        $options = $this->getDefaultIdeAutoCompletionOptions();
        $options[IdeAutoCompletionOptionConstants::APPLICATION_NAME] = 'Glue';

        return $options;
    }

    /**
     * @api
     *
     * @return array<string, mixed>
     */
    public function getGlueBackendIdeAutoCompletionOptions(): array
    {
        $options = $this->getDefaultIdeAutoCompletionOptions();
        $options[IdeAutoCompletionOptionConstants::APPLICATION_NAME] = 'GlueBackend';

        return $options;
    }

    /**
     * @api
     *
     * @return array
     */
    public function getServiceIdeAutoCompletionOptions()
    {
        $options = $this->getDefaultIdeAutoCompletionOptions();
        $options[IdeAutoCompletionOptionConstants::APPLICATION_NAME] = 'Service';

        return $options;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaultIdeAutoCompletionOptions()
    {
        return [
            IdeAutoCompletionOptionConstants::TARGET_BASE_DIRECTORY => APPLICATION_SOURCE_DIR . '/',
            IdeAutoCompletionOptionConstants::TARGET_DIRECTORY_PATTERN => sprintf(
                'Generated/%s/Ide',
                IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER,
            ),
            IdeAutoCompletionOptionConstants::TARGET_NAMESPACE_PATTERN => sprintf(
                'Generated\%s\Ide',
                IdeAutoCompletionConstants::APPLICATION_NAME_PLACEHOLDER,
            ),
            IdeAutoCompletionConstants::DIRECTORY_PERMISSION => $this->getPermissionMode(),
        ];
    }

    /**
     * @api
     *
     * @return array<string, string>
     */
    public function getIdeAutoCompletionSourceDirectoryGlobPatterns()
    {
        return [
            APPLICATION_VENDOR_DIR . '/*/*/src/' => '*/*/',
            APPLICATION_SOURCE_DIR . '/' => $this->get(KernelConstants::PROJECT_NAMESPACE) . '/*/',
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getIdeAutoCompletionGeneratorTemplatePaths()
    {
        return [
            __DIR__ . '/Business/IdeAutoCompletion/Generator/Templates',
        ];
    }

    /**
     * Returns CLI commmand to run the architecture sniffer with [BUNDLE] placeholder
     *
     * @api
     *
     * @return string
     */
    public function getArchitectureSnifferCommand()
    {
        return $this->getPhpMdCommand() . ' ' . static::BUNDLE_PLACEHOLDER . ' xml ' . $this->getArchitectureSnifferRuleset();
    }

    /**
     * Either a relative or full path to the ruleset.xml
     *
     * @api
     *
     * @return string
     */
    public function getArchitectureSnifferRuleset()
    {
        $vendorDir = APPLICATION_VENDOR_DIR . DIRECTORY_SEPARATOR;

        return $vendorDir . 'spryker/architecture-sniffer/src/ruleset.xml';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCodeSnifferRuleset(): string
    {
        return dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'ruleset.xml';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getCodeSnifferStrictRuleset(): string
    {
        return dirname(__DIR__, 4) . DIRECTORY_SEPARATOR . 'rulesetStrict.xml';
    }

    /**
     * Returns the path to the CodeSniffer feature ruleset.
     *
     * @api
     *
     * @return string
     */
    public function getCodeSnifferFeatureRuleset(): string
    {
        return $this->getPathToFeature() . 'phpcs.xml';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPhpMdCommand()
    {
        return 'php -d error_reporting=E_ALL&~E_DEPRECATED vendor/bin/phpmd';
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getProjectNamespaces()
    {
        return $this->get(DevelopmentConstants::PROJECT_NAMESPACES);
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getCoreNamespaces()
    {
        return $this->get(DevelopmentConstants::CORE_NAMESPACES);
    }

    /**
     * Gets default priority for architecture sniffer.
     *
     * @api
     *
     * @return int
     */
    public function getArchitectureSnifferDefaultPriority(): int
    {
        return 2;
    }

    /**
     * Gets PHPStan default level. The higher, the better.
     *
     * Recommended level is 8 (include nullable safety).
     *
     * @api
     *
     * @return int
     */
    public function getPhpstanLevel()
    {
        return 7;
    }

    /**
     * Gets CodeSniffer default level. The higher, the better.
     *
     * @api
     *
     * @return int
     */
    public function getCodeSnifferLevel(): int
    {
        return 1;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getInternalNamespacesList(): array
    {
        return static::INTERNAL_NAMESPACES_LIST;
    }

    /**
     * @api
     *
     * @param string $namespace
     *
     * @return string|null
     */
    public function getPathToInternalNamespace(string $namespace): ?string
    {
        $pathToSprykerRoot = $this->checkPathToSprykerRoot($namespace);
        if ($pathToSprykerRoot) {
            return $pathToSprykerRoot;
        }

        if (array_key_exists($namespace, $this->getPathsToInternalNamespace())) {
            return static::INTERNAL_NAMESPACES_TO_PATH_MAPPING[$namespace];
        }

        return null;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getPathsToInternalNamespace(): array
    {
        $pathToSprykerRoot = $this->checkPathToSprykerRoot(static::NAMESPACE_SPRYKER);
        $sprykerNamespacePath = $pathToSprykerRoot ? [static::NAMESPACE_SPRYKER => $pathToSprykerRoot] : [];

        return $sprykerNamespacePath + static::INTERNAL_NAMESPACES_TO_PATH_MAPPING;
    }

    /**
     * @param string $namespace
     *
     * @return string|null
     */
    protected function checkPathToSprykerRoot(string $namespace): ?string
    {
        if ($namespace === static::NAMESPACE_SPRYKER) {
            // Check for deprecated environment config constant.
            $path = $this->getConfig()->get(KernelConstants::SPRYKER_ROOT);
            if ($path) {
                return rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
        }

        return null;
    }

    /**
     * @api
     *
     * @deprecated Use `spryker/module-finder` instead.
     *
     * @return array<string>
     */
    public function getInternalPackageDirectories(): array
    {
        return static::INTERNAL_PACKAGE_DIRECTORIES;
    }

    /**
     * Specification:
     * - Returns group names to run only tests that have all of the groups.
     * - Example: ['Customer', 'Communication'] inclusive parameter runs tests Communication suite in Customer module.
     *
     * @api
     *
     * @return array<string>
     */
    public function getDefaultInclusiveGroups(): array
    {
        return [
            static::GROUP_SPRYKER_TEST,
        ];
    }

    /**
     * Specification:
     * - Returns Spryker namespace.
     *
     * @api
     *
     * @return string
     */
    public function getNamespaceSpryker(): string
    {
        return static::NAMESPACE_SPRYKER;
    }

    /**
     * Specification:
     * - Returns Spryker Shop namespace.
     *
     * @api
     *
     * @return string
     */
    public function getNamespaceSprykerShop(): string
    {
        return static::NAMESPACE_SPRYKER_SHOP;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getProcessTimeout(): int
    {
        return static::TIMEOUT_DEFAULT;
    }

    /**
     * Specification:
     * - Is used to determine if the application is in the standalone mode e.g. without the project context.
     *
     * @api
     *
     * @return bool
     */
    public function isStandaloneMode(): bool
    {
        return (bool)getenv('DEVELOPMENT_STANDALONE_MODE');
    }

    /**
     * Specification:
     * - Returns Spryker Feature namespace.
     *
     * @api
     *
     * @return string
     */
    public function getSprykerFeatureNamespace(): string
    {
        return static::NAMESPACE_SPRYKER_FEATURE;
    }
}
