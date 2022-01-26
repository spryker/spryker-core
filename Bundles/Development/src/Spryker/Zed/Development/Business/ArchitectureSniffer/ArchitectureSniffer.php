<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\ArchitectureSniffer;

use Exception;
use Laminas\Config\Reader\ReaderInterface;
use PHPMD\RuleSetFactory;
use PHPMD\TextUI\CommandLineOptions;
use RuntimeException;
use Spryker\Zed\Development\Business\SnifferConfiguration\Builder\SnifferConfigurationBuilderInterface;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;

class ArchitectureSniffer implements ArchitectureSnifferInterface
{
    /**
     * @var string
     */
    public const OPTION_PRIORITY = 'priority';

    /**
     * @var string
     */
    public const OPTION_STRICT = 'strict';

    /**
     * @var string
     */
    public const OPTION_DRY_RUN = 'dry-run';

    /**
     * @var string
     */
    public const NAME_VISIBLE_VIOLATIONS = 'visible';

    /**
     * @var string
     */
    public const NAME_IGNORED_VIOLATIONS = 'ignored';

    /**
     * @var string
     */
    public const VIOLATION_FIELD_NAME_DESCRIPTION = 'description';

    /**
     * @var string
     */
    public const VIOLATION_FIELD_NAME_RULESET = 'ruleset';

    /**
     * @var string
     */
    public const VIOLATION_FIELD_NAME_RULE = 'rule';

    /**
     * @var string
     */
    public const OPTION_UPDATE = 'update-baseline';

    /**
     * @var string
     */
    protected const SOURCE_FOLDER_NAME = 'src';

    /**
     * @var string
     */
    protected const OPTION_MODULE = 'module';

    /**
     * @var string
     */
    protected const OPTION_IGNORE_ERRORS = 'ignoreErrors';

    /**
     * @var string
     */
    protected const OPTION_VERBOSE = 'verbose';

    /**
     * @var string
     */
    protected const ARCHITECTURE_BASELINE_JSON = 'architecture-baseline.json';

    /**
     * @var string
     */
    protected const VIOLATION_FIELD_NAME_PRIORITY = 'priority';

    /**
     * @var string
     */
    protected const VIOLATION_FIELD_NAME_FILENAME = 'fileName';

    /**
     * @var string
     */
    protected $command;

    /**
     * @var \Laminas\Config\Reader\ReaderInterface
     */
    protected $xmlReader;

    /**
     * @var \Spryker\Zed\Development\Business\SnifferConfiguration\Builder\SnifferConfigurationBuilderInterface
     */
    protected $configurationBuilder;

    /**
     * @param \Laminas\Config\Reader\ReaderInterface $xmlReader
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

        /** @var callable $sortAlphabetically */
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
     * @param array<string> $options
     *
     * @return array<array<string, mixed>>
     */
    public function run($directory, array $options = []): array
    {
        $options = $this->configurationBuilder->getConfiguration($directory, $options);

        if ($options === []) {
            return [];
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

        return $this->runAnalyzer($fileViolations, $directory, $options);
    }

    /**
     * @param array<string, array<array<string, mixed>>> $fileViolations
     * @param string $directory
     * @param array<string> $options
     *
     * @return array<array<string, mixed>>
     */
    protected function runAnalyzer(array $fileViolations, $directory, array $options): array
    {
        $reportPath = APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . static::ARCHITECTURE_BASELINE_JSON;
        if ($this->isCoreModule($options)) {
            $reportPath = dirname($directory) . DIRECTORY_SEPARATOR . static::ARCHITECTURE_BASELINE_JSON;
        }

        $reportFileExists = file_exists($reportPath);
        $result = $this->formatViolations($fileViolations);
        $reportResult = $reportFileExists ? $this->getReportResult($reportPath) : [];

        if ($options[static::OPTION_UPDATE] || !$reportFileExists) {
            $this->saveBaseline($result, $reportPath);
        }

        if (!$result) {
            $result = $reportResult;
        }

        return $this->sortViolations($result, $reportResult);
    }

    /**
     * @param array $result
     * @param string $reportPath
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function saveBaseline(array $result, $reportPath): void
    {
        $content = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($content === false) {
            throw new RuntimeException('Error encoding:' . json_last_error());
        }

        $content .= PHP_EOL;

        file_put_contents($reportPath, $content);
    }

    /**
     * @param array $result
     * @param array $reportResult
     *
     * @return array
     */
    protected function sortViolations(array $result, array $reportResult): array
    {
        $sortedViolations = [
            static::NAME_VISIBLE_VIOLATIONS => [],
            static::NAME_IGNORED_VIOLATIONS => [],
        ];

        foreach ($result as $key => $violations) {
            if (array_search($violations[static::VIOLATION_FIELD_NAME_DESCRIPTION], array_column($reportResult, static::VIOLATION_FIELD_NAME_DESCRIPTION)) !== false) {
                $sortedViolations[static::NAME_IGNORED_VIOLATIONS][] = $result[$key];
            } else {
                $sortedViolations[static::NAME_VISIBLE_VIOLATIONS][] = $result[$key];
            }
        }

        return $sortedViolations;
    }

    /**
     * @param array<string> $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess(array $command)
    {
        return new Process($command, APPLICATION_ROOT_DIR, null, null, 0);
    }

    /**
     * @param string $directory
     * @param array<string, mixed> $options
     *
     * @throws \Exception
     *
     * @return string|null
     */
    protected function runCommand($directory, array $options = [])
    {
        $command = explode(' ', str_replace(DevelopmentConfig::BUNDLE_PLACEHOLDER, $directory, $this->command));
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
     * @param array<string, array<array<string, mixed>>> $array
     *
     * @return array<array<string, mixed>>
     */
    protected function formatViolations(array $array): array
    {
        $result = [];

        foreach ($array as $file => $violations) {
            foreach ($violations as $violation) {
                $result[] = [
                    static::VIOLATION_FIELD_NAME_FILENAME => $this->normalizeProjectPath($file),
                    static::VIOLATION_FIELD_NAME_DESCRIPTION => $this->cleanDescription($violation['_']),
                    static::VIOLATION_FIELD_NAME_RULE => $violation['rule'],
                    static::VIOLATION_FIELD_NAME_RULESET => $violation['ruleset'],
                    static::VIOLATION_FIELD_NAME_PRIORITY => $violation['priority'],
                ];
            }
        }

        return $result;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function normalizeProjectPath(string $path): string
    {
        $basePath = rtrim(APPLICATION_ROOT_DIR, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $path = str_replace($basePath, '', $path);
        $pos = strpos($path, DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR);
        if ($pos) {
            $path = substr($path, $pos + 1);
        }

        return $path;
    }

    /**
     * @param string $description
     *
     * @return string
     */
    protected function cleanDescription(string $description): string
    {
        return trim($description);
    }

    /**
     * @param string $path
     *
     * @throws \RuntimeException
     *
     * @return array
     */
    protected function getReportResult(string $path): array
    {
        $content = file_get_contents($path);
        if ($content === false) {
            throw new RuntimeException('Invalid content: ' . $path);
        }

        $result = json_decode($content, true);
        if ($result === null) {
            throw new RuntimeException('Invalid JSON file: ' . $path);
        }

        return $result;
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
     * @param array<string, mixed> $options
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
     * @param array<string, mixed> $options
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
     * @param array<string> $ignoreErrorPatterns
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
     * @param array<string> $ignoreErrorPatterns
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
