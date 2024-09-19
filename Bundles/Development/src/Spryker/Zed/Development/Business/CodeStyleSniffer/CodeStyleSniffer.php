<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\CodeStyleSniffer;

use Laminas\Config\Reader\Xml;
use Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface;
use Spryker\Zed\Development\Business\Resolver\PathResolverInterface;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;

class CodeStyleSniffer
{
    /**
     * @var int
     */
    protected const CODE_SUCCESS = 0;

    /**
     * @var string
     */
    protected const OPTION_IGNORE = 'ignore';

    /**
     * @var array<string>
     */
    protected const EXTENSIONS = ['php'];

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @var array <string, mixed>
     */
    protected $options = [];

    /**
     * @var \Spryker\Zed\Development\Business\Resolver\PathResolverInterface
     */
    protected PathResolverInterface $pathResolver;

    /**
     * @var int
     */
    protected $countResolvedPaths = 0;

    /**
     * @var int
     */
    protected $countTotalPaths = 0;

    /**
     * @var array<string>
     */
    protected $commandsToFix = [];

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     * @param \Spryker\Zed\Development\Business\Resolver\PathResolverInterface $pathResolver
     */
    public function __construct(
        DevelopmentConfig $config,
        PathResolverInterface $pathResolver
    ) {
        $this->config = $config;
        $this->pathResolver = $pathResolver;
    }

    /**
     * @param string|null $module
     * @param array<string, mixed> $options
     *
     * @return int
     */
    public function checkCodeStyle(?string $module, array $options = []): int
    {
        $resultCode = static::CODE_SUCCESS;

        $namespace = null;
        if ($module !== null && strpos($module, '.') !== false) {
            [$namespace, $module] = explode('.', $module, 2);
        }

        $pathOption = $options['path'] ?? null;
        $options += $this->getDefaultIgnoredPath($module, $pathOption);

        $paths = $this->pathResolver->resolvePaths($module, $namespace, $pathOption, $options);
        $this->countTotalPaths = count($paths);

        foreach ($paths as $path => $codeStyleSnifferConfiguration) {
            $this->countResolvedPaths++;
            $resultCode |= $this->runSnifferCommand($path, $codeStyleSnifferConfiguration);
        }

        if ($this->commandsToFix) {
            echo 'To fix run the following command: ' . PHP_EOL;
            echo implode('', $this->commandsToFix);
        }

        return $resultCode;
    }

    /**
     * @param string|null $path
     * @param string|null $pathOption
     *
     * @return array<string, string|null>
     */
    protected function getDefaultIgnoredPath(?string $path = null, ?string $pathOption = null): array
    {
        $dontIgnoreVendor = $path || $pathOption || $this->config->isStandaloneMode();

        return [
            static::OPTION_IGNORE => $dontIgnoreVendor ? null : 'vendor/',
        ];
    }

    /**
     * @param string $path
     * @param \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface $codeStyleSnifferConfiguration
     *
     * @return int Exit code
     */
    protected function runSnifferCommand($path, CodeStyleSnifferConfigurationInterface $codeStyleSnifferConfiguration)
    {
        $standard = $codeStyleSnifferConfiguration->getCodingStandard($path);
        $processConfig = '--standard=' . $standard;

        if ($codeStyleSnifferConfiguration->isVerbose()) {
            $processConfig .= ' -v';
        }

        if (!$codeStyleSnifferConfiguration->isQuiet()) {
            $processConfig .= ' -p';
        }

        if ($codeStyleSnifferConfiguration->isExplaining()) {
            $processConfig .= ' -e';
        }

        $optionSniffs = $codeStyleSnifferConfiguration->getSpecificSniffs();
        if ($optionSniffs) {
            $processConfig .= ' --sniffs=' . $optionSniffs;
        }

        $processConfig .= ' --extensions=' . implode(',', static::EXTENSIONS);

        $optionIgnore = $codeStyleSnifferConfiguration->getIgnoredPaths();

        $customPaths = [];
        $hasConfigFile = file_exists($path . DIRECTORY_SEPARATOR . 'phpcs.xml');
        if (!$hasConfigFile) {
            if (is_dir($path . 'src')) {
                $customPaths[] = $path . 'src/';
            }
            if (is_dir($path . 'tests')) {
                $customPaths[] = $path . 'tests/';
            }
            $optionIgnore .= ($optionIgnore ? ',' : '') . '/src/Generated/';
        }

        if ($optionIgnore) {
            $processConfig .= ' --ignore=' . $optionIgnore;
        }

        $processConfig .= ' ' . implode(' ', $customPaths);

        $optionVerbose = $codeStyleSnifferConfiguration->isVerbose();
        $optionFix = $codeStyleSnifferConfiguration->isFixing();

        if ($optionVerbose && !$optionFix) {
            $processConfig .= ' -s';
        }

        if (!$hasConfigFile || $this->hasLegacyConfiguration($path . DIRECTORY_SEPARATOR . 'phpcs.xml')) {
            $path = ' ' . $path;
        } else {
            $path = '';
        }

        $command = sprintf(
            'vendor/bin/%s %s%s',
            $optionFix ? 'phpcbf' : 'phpcs',
            $processConfig,
            $customPaths ? '' : $path,
        );

        $optionDryRun = $codeStyleSnifferConfiguration->isDryRun();

        if ($optionDryRun) {
            echo $command . PHP_EOL;

            return static::CODE_SUCCESS;
        }

        $process = new Process(explode(' ', $command), $this->config->getPathToRoot());
        $process->setTimeout($this->config->getProcessTimeout());

        $module = $codeStyleSnifferConfiguration->getModule();
        if ($module !== null && strpos($module, '.all') !== false) {
            return $this->runSnifferCommandForAll($process, $path, $codeStyleSnifferConfiguration);
        }

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        return $process->getExitCode();
    }

    /**
     * @param \Symfony\Component\Process\Process $process
     * @param string $path
     * @param \Spryker\Zed\Development\Business\CodeStyleSniffer\Config\CodeStyleSnifferConfigurationInterface $codeStyleSnifferConfiguration
     *
     * @return int
     */
    protected function runSnifferCommandForAll(
        Process $process,
        string $path,
        CodeStyleSnifferConfigurationInterface $codeStyleSnifferConfiguration
    ): int {
        $process->run();

        echo sprintf(
            'Finished %s/%s %s (level %s) /%s %s' . PHP_EOL,
            $this->countResolvedPaths,
            $this->countTotalPaths,
            basename($path),
            $codeStyleSnifferConfiguration->getLevel(),
            $this->getSnifferResultMessage($process),
            ($process->getExitCode() !== static::CODE_SUCCESS ? $process->getOutput() : ''),
        );

        if ($process->getExitCode() !== static::CODE_SUCCESS && !$codeStyleSnifferConfiguration->isFixing()) {
            $this->commandsToFix[] = sprintf('vendor/bin/console c:s:s -m %s.%s -f' . PHP_EOL, $codeStyleSnifferConfiguration->getNamespace(), basename($path));
        }

        return $process->getExitCode();
    }

    /**
     * @param \Symfony\Component\Process\Process $process
     *
     * @return string
     */
    protected function getSnifferResultMessage(Process $process): string
    {
        return sprintf('%s', $process->getExitCode() === static::CODE_SUCCESS ? "\033[32m OK \033[0m" : "\033[31m FAIL \033[0m");
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function hasLegacyConfiguration(string $path): bool
    {
        $xml = (new Xml())->fromFile($path);

        return empty($xml['file']);
    }
}
