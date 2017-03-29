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

    /**
     * @var string
     */
    protected $command;

    /**
     * @var \Zend\Config\Reader\Xml
     */
    protected $xmlReader;

    /**
     * @param \Zend\Config\Reader\Xml $xmlReader
     * @param $command
     */
    public function __construct(Xml $xmlReader, $command)
    {
        $this->xmlReader = $xmlReader;
        $this->command = $command;
    }

    /**
     * @param $directory
     *
     * @return array
     */
    public function run($directory)
    {
        $output = $this->runCommand($directory);
        $results = $this->xmlReader->fromString($output);
        $fileViolations = $this->formatResult($results);

        return $fileViolations;
    }

    /**
     * @param $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess($command)
    {
        return new Process($command);
    }

    /**
     * @param $directory
     *
     * @return string
     */
    protected function runCommand($directory)
    {
        $command = str_replace(DevelopmentConfig::BUNDLE_PLACEHOLDER, $directory, $this->command);
        $p = $this->getProcess($command);
        $p->setWorkingDirectory(APPLICATION_ROOT_DIR);
        $p->run();
        $output = $p->getOutput();
        return $output;
    }

    /**
     * @param $results
     *
     * @return array
     */
    protected function formatResult($results)
    {
        $fileViolations = [];

        if (!is_array($results) || !array_key_exists('file', $results)) {
            return $fileViolations;
        }

        $fileViolations = $this->formatSingleFileResults($results, $fileViolations);

        $fileViolations = $this->formatMultiFileResults($results, $fileViolations);

        return $fileViolations;
    }

    /**
     * @param $results
     * @param $fileViolations
     *
     * @return mixed
     */
    protected function formatMultiFileResults($results, $fileViolations)
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
     * @param $results
     * @param $fileViolations
     *
     * @return mixed
     */
    protected function formatSingleFileResults($results, $fileViolations)
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
