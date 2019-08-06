<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\ArchitectureSniffer;

use Exception;
use PHPMD\RuleSetFactory;
use PHPMD\TextUI\CommandLineOptions;
use Spryker\Zed\Development\Business\SnifferConfiguration\Builder\SnifferConfigurationBuilderInterface;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;
use Zend\Config\Reader\ReaderInterface;

class ArchitectureSniffer implements ArchitectureSnifferInterface
{
    public const OPTION_PRIORITY = 'priority';
    public const OPTION_STRICT = 'strict';
    public const OPTION_DRY_RUN = 'dry-run';

    protected const SOURCE_FOLDER_NAME = 'src';
    protected const OPTION_MODULE = 'module';
    protected const OPTION_IGNORE_ERRORS = 'ignoreErrors';

    /**
     * @var string
     */
    protected $command;

    /**
     * @var \Zend\Config\Reader\ReaderInterface
     */
    protected $xmlReader;

    /**
     * @var \Spryker\Zed\Development\Business\SnifferConfiguration\Builder\SnifferConfigurationBuilderInterface
     */
    protected $configurationBuilder;

    /**
     * @param \Zend\Config\Reader\ReaderInterface $xmlReader
     * @param string $command
     * @param \Spryker\Zed\Development\Business\SnifferConfiguration\Builder\SnifferConfigurationBuilderInterface $configurationBuilder
     */
    public function __construct(
        ReaderInterface $xmlReader,
        $command,
        SnifferConfigurationBuilderInterface $configurationBuilder
    ) {
        $this->xmlReader = $xmlReader;
        $this->command = $command;
        $this->configurationBuilder = $configurationBuilder;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        $ruleSetFactory = new RuleSetFactory();

        $args = explode(' ', $this->command);
        $options = new CommandLineOptions($args, $ruleSetFactory->listAvailableRuleSets());

        $rules = [];
        foreach ($ruleSetFactory->createRuleSets($options->getRuleSets()) as $ruleSet) {
            /** @var \PHPMD\AbstractRule $rule */
            foreach ($ruleSet->getRules() as $rule) {
                $rules[$rule->getName()] = [
                    'name' => $rule->getName(),
                    'ruleset' => $rule->getRuleSetName(),
                    'description' => $rule->getDescription(),
                    'priority' => $rule->getPriority(),
                    'rule' => $rule,
                ];
            }
        }

        $sortAlphabetically = function ($first, $second) {
            return strcasecmp($first['name'], $second['name']) < 0;
        };
        usort($rules, $sortAlphabetically);

        $sortPriority = function ($first, $second) {
            return $first['priority'] - $second['priority'];
        };
        usort($rules, $sortPriority);

        return $rules;
    }

    /**
     * @param string $directory
     * @param array $options
     *
     * @return array
     */
    public function run($directory, array $options = [])
    {
        $options = $this->configurationBuilder->getConfiguration($directory, $options);

        if ($options === []) {
            return $this->formatResult($options);
        }

        if ($this->isCoreModule($options)) {
            $directory = $this->addSourcePathForCoreModulePath($directory);
        }

        if (!file_exists($directory)) {
            return $this->formatResult($options);
        }

        $output = $this->runCommand($directory, $options);
        $results = $this->xmlReader->fromString($output);

        if (!is_array($results)) {
            $results = [];
        }

        $results = $this->getResultsWithoutIgnoredErrors($results, $options);
        $fileViolations = $this->formatResult($results);

        return $fileViolations;
    }

    /**
     * @param array $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess(array $command)
    {
        return new Process($command, APPLICATION_ROOT_DIR, null, null, 0);
    }

    /**
     * @param string $directory
     * @param array $options
     *
     * @throws \Exception
     *
     * @return string|null
     */
    protected function runCommand($directory, array $options = [])
    {
        $command = [str_replace(DevelopmentConfig::BUNDLE_PLACEHOLDER, $directory, $this->command)];
        $command[] = '--minimumpriority';
        $command[] = $options[static::OPTION_PRIORITY];

        if (!empty($options[static::OPTION_STRICT])) {
            $command[] = '--strict';
        }

        if (!empty($options[static::OPTION_DRY_RUN])) {
            $this->displayAndExit($command);
        }

        $process = $this->getProcess($command);
        $process->run();
        if (substr($process->getOutput(), 0, 5) !== '<?xml') {
            throw new Exception('Sniffer run was not successful: ' . $process->getExitCodeText());
        }

        $output = $process->getOutput();

        return $output;
    }

    /**
     * @param array $command
     *
     * @return void
     */
    protected function displayAndExit(array $command)
    {
        exit(implode(' ', $command) . PHP_EOL);
    }

    /**
     * @param array $results
     *
     * @return array
     */
    protected function formatResult(array $results)
    {
        $fileViolations = [];

        if (!array_key_exists('file', $results)) {
            return $fileViolations;
        }

        $fileViolations = $this->formatSingleFileResults($results, $fileViolations);

        $fileViolations = $this->formatMultiFileResults($results, $fileViolations);

        return $fileViolations;
    }

    /**
     * @param array $results
     * @param array $fileViolations
     *
     * @return array
     */
    protected function formatMultiFileResults(array $results, array $fileViolations)
    {
        foreach ($results['file'] as $file) {
            if (!is_array($file)) {
                continue;
            }

            if (array_key_exists('violation', $file)) {
                if (!array_key_exists($file['name'], $fileViolations)) {
                    $fileViolations[$file['name']] = [];
                }

                if (array_key_exists('_', $file['violation'])) {
                    $fileViolations[$file['name']][] = $file['violation'];
                } else {
                    foreach ($file['violation'] as $violation) {
                        $fileViolations[$file['name']][] = $violation;
                    }
                }
            }
        }

        return $fileViolations;
    }

    /**
     * @param array $results
     * @param array $fileViolations
     *
     * @return array
     */
    protected function formatSingleFileResults(array $results, array $fileViolations)
    {
        if (array_key_exists('violation', $results['file'])) {
            if (array_key_exists('_', $results['file']['violation'])) {
                $fileViolations[$results['file']['name']][] = $results['file']['violation'];

                return $fileViolations;
            } else {
                $fileViolations[$results['file']['name']] = $results['file']['violation'];

                return $fileViolations;
            }
        }

        return $fileViolations;
    }

    /**
     * @param string $directory
     *
     * @return string
     */
    protected function addSourcePathForCoreModulePath(string $directory): string
    {
        return $directory . static::SOURCE_FOLDER_NAME . DIRECTORY_SEPARATOR;
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    protected function isCoreModule(array $options): bool
    {
        if (!isset($options[static::OPTION_MODULE])) {
            return false;
        }

        $module = $options[static::OPTION_MODULE];

        return mb_strpos($module, '.') !== false;
    }

    /**
     * @param array $results
     * @param array $options
     *
     * @return array
     */
    protected function getResultsWithoutIgnoredErrors(array $results, array $options): array
    {
        if ($results === []) {
            return $results;
        }

        $ignoreErrorPatterns = $options[static::OPTION_IGNORE_ERRORS];

        if ($ignoreErrorPatterns === []) {
            return $results;
        }

        if (!array_key_exists('file', $results)) {
            return $results;
        }

        if (array_key_exists('violation', $results['file'])) {
            $results['file'] = $this->filterOutIgnoredErrors($results['file'], $ignoreErrorPatterns);

            return $results;
        }

        $fileResults = [];

        foreach ($results['file'] as $index => $fileResult) {
            $fileResults[$index] = $this->filterOutIgnoredErrors($fileResult, $ignoreErrorPatterns);
        }

        $results['file'] = $fileResults;

        return $results;
    }

    /**
     * @param array $fileResult
     * @param string[] $ignoreErrorPatterns
     *
     * @return array
     */
    protected function filterOutIgnoredErrors(array $fileResult, array $ignoreErrorPatterns): array
    {
        if (!array_key_exists('violation', $fileResult)) {
            return $fileResult;
        }

        if (array_key_exists('_', $fileResult['violation'])) {
            $violation = $fileResult['violation'];

            if (!$this->isViolationMatchWithIgnoreErrorPatterns($violation, $ignoreErrorPatterns)) {
                $fileResult['violation'] = $violation;
            }

            return $fileResult;
        }

        $violations = [];

        foreach ($fileResult['violation'] as $index => $violation) {
            if (!$this->isViolationMatchWithIgnoreErrorPatterns($violation, $ignoreErrorPatterns)) {
                $violations[$index] = $violation;
            }
        }

        $fileResult['violation'] = $violations;

        return $fileResult;
    }

    /**
     * @param array $violation
     * @param string[] $ignoreErrorPatterns
     *
     * @return bool
     */
    protected function isViolationMatchWithIgnoreErrorPatterns(array $violation, array $ignoreErrorPatterns): bool
    {
        $violationMessage = trim($violation['_']);

        foreach ($ignoreErrorPatterns as $ignoreErrorPattern) {
            if (preg_match($ignoreErrorPattern, $violationMessage)) {
                return true;
            }
        }

        return false;
    }
}
