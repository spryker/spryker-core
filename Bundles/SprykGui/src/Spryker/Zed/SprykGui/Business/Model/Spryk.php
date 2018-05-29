<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Model;

use Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface;
use Symfony\Component\Process\Process;
use Zend\Filter\FilterChain;
use Zend\Filter\Word\CamelCaseToSeparator;

class Spryk implements SprykInterface
{
    /**
     * @var \Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface
     */
    protected $sprykFacade;

    /**
     * @param \Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface $sprykFacade
     */
    public function __construct(SprykGuiToSprykFacadeInterface $sprykFacade)
    {
        $this->sprykFacade = $sprykFacade;
    }

    /**
     * @return array
     */
    public function getSprykDefinitions(): array
    {
        return $this->organizeSprykDefinitions(
            $this->sprykFacade->getSprykDefinitions()
        );
    }

    /**
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return array
     */
    public function buildSprykView(string $sprykName, array $sprykArguments): array
    {
        $commandLine = $this->getCommandLine($sprykName, $sprykArguments);
        $jiraTemplate = $this->getJiraTemplate($sprykName, $commandLine, $sprykArguments);

        return [
            'commandLine' => $commandLine,
            'jiraTemplate' => $jiraTemplate,
        ];
    }

    /**
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return bool
     */
    public function runSpryk(string $sprykName, array $sprykArguments): bool
    {
        $commandLine = $this->getCommandLine($sprykName, $sprykArguments);
        $process = new Process($commandLine, APPLICATION_ROOT_DIR);
        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        return true;
    }

    /**
     * @param array $sprykDefinitions
     *
     * @return array
     */
    protected function organizeSprykDefinitions(array $sprykDefinitions): array
    {
        $organized = [];

        foreach ($sprykDefinitions as $sprykName => $sprykDefinition) {
            $application = $this->getApplicationBySprykName($sprykName);
            if (!isset($organized[$application])) {
                $organized[$application] = [];
            }
            $organized[$application][$sprykName] = [
                'humanized' => $this->createHumanizeFilter()->filter($sprykName),
                'description' => $sprykDefinition['description'],
            ];

            ksort($organized[$application]);
        }

        return $organized;
    }

    /**
     * @param string $sprykName
     *
     * @return string
     */
    protected function getApplicationBySprykName(string $sprykName): string
    {
        $humanizedSprykName = $this->createHumanizeFilter()->filter($sprykName);
        $humanizedSprykNameFragments = explode(' ', $humanizedSprykName);
        $applications = ['Client', 'Shared', 'Yves', 'Zed'];

        if (in_array($humanizedSprykNameFragments[1], $applications)) {
            return $humanizedSprykNameFragments[1];
        }

        return 'Common';
    }

    /**
     * @return \Zend\Filter\FilterChain
     */
    protected function createHumanizeFilter(): FilterChain
    {
        $filterChain = new FilterChain();
        $filterChain->attach(new CamelCaseToSeparator(' '));

        return $filterChain;
    }

    /**
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return string
     */
    protected function getCommandLine(string $sprykName, array $sprykArguments): string
    {
        $arguments = $this->buildArgumentString($sprykArguments);
        $commandLine = sprintf('vendor/bin/console spryk:run %s %s -n', $sprykName, $arguments);

        return $commandLine;
    }

    /**
     * @param array $sprykArguments
     *
     * @return string
     */
    protected function buildArgumentString(array $sprykArguments)
    {
        $addedArguments = [];
        $argumentString = '';
        $sprykDefinitions = $this->sprykFacade->getSprykDefinitions();
        foreach ($sprykArguments as $sprykName => $userArguments) {
            $sprykDefinition = $sprykDefinitions[$sprykName];
            foreach ($sprykDefinition['arguments'] as $argumentName => $argumentDefinition) {
                if (isset($argumentDefinition['value']) || isset($argumentDefinition['callbackOnly'])) {
                    continue;
                }

                $userInput = $userArguments[$argumentName];
                if (isset($addedArguments[$argumentName]) && ($userInput !== $addedArguments[$argumentName])) {
                    $argumentName = sprintf('%s.%s', $sprykName, $argumentName);
                }

                if (isset($addedArguments[$argumentName])) {
                    continue;
                }

                if ((!isset($argumentDefinition['default'])) || (isset($argumentDefinition['default']) && $argumentDefinition['default'] !== $userInput)) {
                    if (!isset($argumentDefinition['multiline'])) {
                        $argumentString .= sprintf(' --%s=%s', $argumentName, escapeshellarg($userInput));
                        $addedArguments[$argumentName] = $userInput;

                        continue;
                    }

                    $lines = explode(PHP_EOL, $userInput);
                    foreach ($lines as $line) {
                        $line = preg_replace('/[[:cntrl:]]/', '', $line);
                        $argumentString .= sprintf(' --%s=%s', $argumentName, escapeshellarg($line));
                    }

                    $addedArguments[$argumentName] = $userInput;
                }
            }
        }

        return $argumentString;
    }

    /**
     * @param string $sprykName
     * @param string $commandLine
     * @param array $sprykArguments
     *
     * @return string
     */
    protected function getJiraTemplate(string $sprykName, string $commandLine, array $sprykArguments): string
    {
        $jiraTemplate = PHP_EOL . sprintf('{code:title=%s|theme=Midnight|linenumbers=true|collapse=true}', $sprykName) . PHP_EOL;
        $jiraTemplate .= $commandLine . PHP_EOL . PHP_EOL;

        $addedArguments = [];

        $sprykDefinitions = $this->sprykFacade->getSprykDefinitions();
        foreach ($sprykArguments as $sprykName => $userArguments) {
            $sprykDefinition = $sprykDefinitions[$sprykName];
            foreach ($sprykDefinition['arguments'] as $argumentName => $argumentDefinition) {
                if (isset($argumentDefinition['value']) || isset($argumentDefinition['callbackOnly'])) {
                    continue;
                }

                $userInput = $userArguments[$argumentName];
                if (isset($addedArguments[$argumentName]) && ($userInput !== $addedArguments[$argumentName])) {
                    $argumentName = sprintf('%s.%s', $sprykName, $argumentName);
                }

                if (isset($addedArguments[$argumentName])) {
                    continue;
                }

                if ((!isset($argumentDefinition['default'])) || (isset($argumentDefinition['default']) && $argumentDefinition['default'] !== $userInput)) {
                    $jiraTemplate .= sprintf('"%s"', $argumentName) . PHP_EOL;

                    if (!isset($argumentDefinition['multiline'])) {
                        $jiraTemplate .= sprintf('// %s', $userInput) . PHP_EOL . PHP_EOL;

                        $addedArguments[$argumentName] = $userInput;

                        continue;
                    }

                    $lines = explode(PHP_EOL, $userInput);
                    foreach ($lines as $line) {
                        $line = preg_replace('/[[:cntrl:]]/', '', $line);
                        $jiraTemplate .= sprintf('// %s', $line) . PHP_EOL;
                    }

                    $jiraTemplate .= PHP_EOL;

                    $addedArguments[$argumentName] = $userInput;
                }
            }
        }

        $jiraTemplate .= '{code}';

        return $jiraTemplate;
    }
}
