<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Spryk;

use Generated\Shared\Transfer\ArgumentTransfer;
use Spryker\Zed\SprykGui\Business\Graph\GraphBuilderInterface;
use Spryker\Zed\SprykGui\Business\Spryk\Form\FormDataNormalizer;
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
     * @var \Spryker\Zed\SprykGui\Business\Graph\GraphBuilderInterface
     */
    protected $graphBuilder;

    /**
     * @param \Spryker\Zed\SprykGui\Dependency\Facade\SprykGuiToSprykFacadeInterface $sprykFacade
     * @param \Spryker\Zed\SprykGui\Business\Graph\GraphBuilderInterface $graphBuilder
     */
    public function __construct(SprykGuiToSprykFacadeInterface $sprykFacade, GraphBuilderInterface $graphBuilder)
    {
        $this->sprykFacade = $sprykFacade;
        $this->graphBuilder = $graphBuilder;
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
     * @param string $spryk
     *
     * @return array
     */
    public function getSprykDefinitionByName(string $spryk): array
    {
        $sprykDefinitions = $this->sprykFacade->getSprykDefinitions();

        return $sprykDefinitions[$spryk];
    }

    /**
     * @param string $sprykName
     * @param array $formData
     *
     * @return array
     */
    public function buildSprykView(string $sprykName, array $formData): array
    {
        $normalizedFormData = (new FormDataNormalizer())->normalizeFormData($formData);
        $commandLine = $this->getCommandLine($sprykName, $normalizedFormData);
        $jiraTemplate = $this->getJiraTemplate($sprykName, $commandLine, $normalizedFormData);

        return [
            'commandLine' => $commandLine,
            'jiraTemplate' => $jiraTemplate,
        ];
    }

    /**
     * @param string $sprykName
     * @param array $formData
     *
     * @return string
     */
    public function runSpryk(string $sprykName, array $formData): string
    {
        $normalizedFormData = (new FormDataNormalizer())->normalizeFormData($formData);
        $commandLine = $this->getCommandLine($sprykName, $normalizedFormData);
        $process = new Process($commandLine, APPLICATION_ROOT_DIR);
        $process->run();

        if ($process->isSuccessful()) {
            return $process->getOutput();
        }

        return $process->getErrorOutput();
    }

    /**
     * @param string $sprykName
     *
     * @return string
     */
    public function drawSpryk(string $sprykName): string
    {
        return $this->graphBuilder->drawSpryk($sprykName);
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
                'priority' => isset($sprykDefinition['priority']) ? $sprykDefinition['priority'] : '',
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
     * @param array $formData
     *
     * @return string
     */
    protected function getCommandLine(string $sprykName, array $formData): string
    {
        $commandLineArguments = $this->getSprykArguments($sprykName, $formData);

        $commandLine = '';
        foreach ($commandLineArguments as $argumentKey => $argumentValue) {
            $argumentValues = (array)$argumentValue;
            foreach ($argumentValues as $innerArgumentValue) {
                $commandLine .= sprintf(' --%s=%s', $argumentKey, escapeshellarg($innerArgumentValue));
            }
        }

        $commandLine = sprintf('vendor/bin/console spryk:run %s %s -n', $sprykName, $commandLine);

        return $commandLine;
    }

    /**
     * @param string $sprykName
     * @param array $formData
     *
     * @return array
     */
    protected function getSprykArguments(string $sprykName, array $formData): array
    {
        $commandLineArguments = [];

        $sprykDefinition = $this->getSprykDefinitionByName($sprykName);

        $filteredSprykArguments = $this->filterSprykArguments($sprykDefinition, $formData);

        foreach ($filteredSprykArguments as $argumentName => $argumentDefinition) {
            $userInput = $this->getUserInputForArgument($argumentName, $formData);
            if (isset($argumentDefinition['multiline'])) {
                $userInput = $this->getMultilineConsoleArgument($userInput);
            }
            if (isset($argumentDefinition['isMultiple'])) {
                if ($argumentName === 'constructorArguments') {
                    $commandLineArguments['constructorArguments'] = $userInput;
                    $commandLineArguments['dependencyMethods'] = $this->getUserInputForArgument('dependencyMethods', $formData);
                    continue;
                }
            }

            $commandLineArguments[$argumentName] = $userInput;
        }

        return $commandLineArguments;
    }

    /**
     * @param string $userInput
     *
     * @return array
     */
    protected function getMultilineConsoleArgument(string $userInput): array
    {
        $lines = explode(PHP_EOL, $userInput);
        $userInput = [];
        foreach ($lines as $line) {
            $line = preg_replace('/[[:cntrl:]]/', '', $line);
            $userInput[] = $line;
        }

        return $userInput;
    }

    /**
     * @param string $argumentName
     * @param array $formData
     *
     * @return mixed
     */
    protected function getUserInputForArgument(string $argumentName, array $formData)
    {
        return $formData[$argumentName];
    }

    /**
     * @param array $sprykDefinition
     * @param array $formData
     *
     * @return array
     */
    protected function filterSprykArguments(array $sprykDefinition, array $formData)
    {
        $sprykArguments = [];

        foreach ($sprykDefinition['arguments'] as $argumentName => $argumentDefinition) {
            if (isset($argumentDefinition['value']) || isset($argumentDefinition['callbackOnly'])) {
                continue;
            }

            $userInput = $this->getUserInputForArgument($argumentName, $formData);
            if (isset($argumentDefinition['default']) && $argumentDefinition['default'] === $userInput) {
                continue;
            }

            $sprykArguments[$argumentName] = $argumentDefinition;
        }

        return $sprykArguments;
    }

    /**
     * @param array $argumentDefinition
     *
     * @return \Generated\Shared\Transfer\ArgumentTransfer
     */
    protected function getArgumentTransferFromDefinition(array $argumentDefinition): ArgumentTransfer
    {
        return $argumentDefinition['argument'];
    }

    /**
     * @param \Generated\Shared\Transfer\ArgumentTransfer $argumentTransfer
     *
     * @return string
     */
    protected function buildFromArgument(ArgumentTransfer $argumentTransfer)
    {
        $pattern = '%s %s';
        if ($argumentTransfer->getIsOptional()) {
            $pattern = '?%s %s = null';
        }

        return sprintf($pattern, $argumentTransfer->getType(), $argumentTransfer->getVariable());
    }

    /**
     * @param string $sprykName
     * @param string $commandLine
     * @param array $formData
     *
     * @return string
     */
    protected function getJiraTemplate(string $sprykName, string $commandLine, array $formData): string
    {
        $jiraTemplate = PHP_EOL . sprintf('{code:title=%s|theme=Midnight|linenumbers=true|collapse=true}', $sprykName) . PHP_EOL;
        $jiraTemplate .= $commandLine . PHP_EOL . PHP_EOL;

        $sprykArguments = $this->getSprykArguments($sprykName, $formData);

        foreach ($sprykArguments as $argumentName => $argumentValue) {
            $jiraTemplate .= sprintf('"%s"', $argumentName) . PHP_EOL;
            $argumentValues = (array)$argumentValue;
            foreach ($argumentValues as $innerArgumentValue) {
                $jiraTemplate .= sprintf('// %s', $innerArgumentValue) . PHP_EOL;
            }
            $jiraTemplate .= PHP_EOL;
        }

        $jiraTemplate .= '{code}';

        return $jiraTemplate;
    }
}
