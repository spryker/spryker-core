<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\ArchitectureSniffer;

use Exception;
use PHPMD\RuleSetFactory;
use PHPMD\TextUI\CommandLineOptions;
use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;
use Zend\Config\Reader\ReaderInterface;

class ArchitectureSniffer implements ArchitectureSnifferInterface
{
    public const OPTION_PRIORITY = 'priority';
    public const OPTION_STRICT = 'strict';
    public const OPTION_DRY_RUN = 'dry-run';

    /**
     * @var string
     */
    protected $command;

    /**
     * @var \Zend\Config\Reader\ReaderInterface
     */
    protected $xmlReader;

    /**
     * @var int
     */
    protected $defaultPriority;

    /**
     * @param \Zend\Config\Reader\ReaderInterface $xmlReader
     * @param string $command
     * @param int $defaultPriority
     */
    public function __construct(ReaderInterface $xmlReader, $command, $defaultPriority)
    {
        $this->xmlReader = $xmlReader;
        $this->command = $command;
        $this->defaultPriority = $defaultPriority;
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
        $output = $this->runCommand($directory, $options);
        $results = $this->xmlReader->fromString($output);

        if (!is_array($results)) {
            $results = [];
        }

        $fileViolations = $this->formatResult($results);

        return $fileViolations;
    }

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess($command)
    {
        return new Process($command, null, null, null, 0);
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
        $command = str_replace(DevelopmentConfig::BUNDLE_PLACEHOLDER, $directory, $this->command);

        $priority = !empty($options[static::OPTION_PRIORITY]) ? $options[static::OPTION_PRIORITY] : $this->defaultPriority;
        $command .= ' --minimumpriority ' . $priority;

        if (!empty($options[static::OPTION_STRICT])) {
            $command .= ' --strict';
        }

        if (!empty($options[static::OPTION_DRY_RUN])) {
            $this->displayAndExit($command);
        }

        $p = $this->getProcess($command);

        $p->setWorkingDirectory(APPLICATION_ROOT_DIR);
        $p->run();
        if (substr($p->getOutput(), 0, 5) !== '<?xml') {
            throw new Exception('Sniffer run was not successful: ' . $p->getExitCodeText());
        }

        $output = $p->getOutput();
        return $output;
    }

    /**
     * @param string $command
     *
     * @return void
     */
    protected function displayAndExit($command)
    {
        exit($command . PHP_EOL);
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
}
