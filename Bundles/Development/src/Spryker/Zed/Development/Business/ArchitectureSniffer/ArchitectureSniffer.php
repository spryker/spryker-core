<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\ArchitectureSniffer;

use Spryker\Zed\Development\DevelopmentConfig;
use Symfony\Component\Process\Process;
use Zend\Config\Reader\Xml;

class ArchitectureSniffer
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
     * @param $bundle
     *
     * @return array
     */
    public function run($bundle)
    {
        $output = $this->runCommand($bundle);
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
     * @param $bundle
     *
     * @return string
     */
    protected function runCommand($bundle)
    {
        $command = str_replace(DevelopmentConfig::BUNDLE_PLACEHOLDER, $bundle, $this->command);

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

        if (array_key_exists('violation', $results['file'])) {
            if (array_key_exists('_', $results['file']['violation'])) {
                $fileViolations[$results['file']['name']][] = $results['file']['violation'];
            } else {
                $fileViolations[$results['file']['name']] = $results['file']['violation'];
            }

        }

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

}
