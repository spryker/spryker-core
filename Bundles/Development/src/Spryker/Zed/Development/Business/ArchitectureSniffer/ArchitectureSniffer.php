<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\ArchitectureSniffer;

use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;
use Zend\Config\Reader\Xml;

class ArchitectureSniffer implements ArchitectureSnifferInterface
{

    const OPTION_PRIORITY = 'priority';
    const OPTION_STRICT = 'strict';

    /**
     * @var string
     */
    protected $command;

    /**
     * @var \Zend\Config\Reader\Xml
     */
    protected $xmlReader;

    /**
     * @var int
     */
    private $defaultPriority;

    /**
     * @param \Zend\Config\Reader\Xml $xmlReader
     * @param string $command
     */
    public function __construct(Xml $xmlReader, $command, $defaultPriority)
    {
        $this->xmlReader = $xmlReader;
        $this->command = $command;
        $this->defaultPriority = $defaultPriority;
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
     * @return string
     */
    protected function runCommand($directory, array $options = [])
    {
        $command = str_replace(DevelopmentConfig::BUNDLE_PLACEHOLDER, $directory, $this->command);

        $priority = !empty($options[static::OPTION_PRIORITY]) ? $options[static::OPTION_PRIORITY] : $this->defaultPriority;
        $command .= ' --minimumpriority ' . $priority;

        if (!empty($options[static::OPTION_STRICT])) {
            $command .= ' --strict';
        }

        $p = $this->getProcess($command);

        $p->setWorkingDirectory(APPLICATION_ROOT_DIR);
        $p->run();
        $output = $p->getOutput();
        return $output;
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
