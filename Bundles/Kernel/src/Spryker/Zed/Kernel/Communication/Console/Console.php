<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication\Console;

use RuntimeException;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver;
use Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\Kernel\RepositoryResolverAwareTrait;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Symfony\Component\Console\Application getApplication()
 */
class Console extends SymfonyCommand
{
    const CODE_SUCCESS = 0;
    const CODE_ERROR = 1;

    use RepositoryResolverAwareTrait;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    private $factory;

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    private $facade;

    /**
     * @var \Spryker\Zed\Kernel\Container
     */
    private $container;

    /**
     * @var \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    private $queryContainer;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $messenger;

    /**
     * @var int
     */
    private $exitCode = self::CODE_SUCCESS;

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return $this
     */
    public function setExternalDependencies(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractCommunicationFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        if ($this->container !== null) {
            $this->factory->setContainer($this->container);
        }

        if ($this->queryContainer !== null) {
            $this->factory->setQueryContainer($this->queryContainer);
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    private function resolveFactory()
    {
        return $this->getFactoryResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Factory\FactoryResolver
     */
    private function getFactoryResolver()
    {
        return new FactoryResolver();
    }

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractFacade $facade
     *
     * @return void
     */
    public function setFacade(AbstractFacade $facade)
    {
        $this->facade = $facade;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->resolveFacade();
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    private function resolveFacade()
    {
        return $this->getFacadeResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Facade\FacadeResolver
     */
    private function getFacadeResolver()
    {
        return new FacadeResolver();
    }

    /**
     * @param \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer $queryContainer
     *
     * @return $this
     */
    public function setQueryContainer(AbstractQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->queryContainer;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @param string $command
     * @param array $arguments
     *
     * @return int
     */
    protected function runDependingCommand($command, array $arguments = [])
    {
        $command = $this->getApplication()->find($command);
        $arguments['command'] = $command->getName();
        $input = new ArrayInput($arguments);

        $exitCode = $command->run($input, $this->output);

        $this->setExitCode($exitCode);

        return $exitCode;
    }

    /**
     * @param int $exitCode
     *
     * @return $this
     */
    private function setExitCode($exitCode)
    {
        $this->exitCode = $exitCode;

        return $this;
    }

    /**
     * @return bool
     */
    protected function hasError()
    {
        return $this->exitCode !== self::CODE_SUCCESS;
    }

    /**
     * @return int
     */
    protected function getLastExitCode()
    {
        return $this->exitCode;
    }

    /**
     * @return \Psr\Log\LoggerInterface|\Symfony\Component\Console\Logger\ConsoleLogger
     */
    protected function getMessenger()
    {
        if ($this->messenger === null) {
            $this->messenger = new ConsoleLogger($this->output);
        }

        return $this->messenger;
    }

    /**
     * @param string|array $message
     * @param bool $wrapInInfoTags
     *
     * @return void
     */
    public function info($message, $wrapInInfoTags = true)
    {
        if (is_array($message)) {
            $message = implode(PHP_EOL, $message);
        }
        if ($wrapInInfoTags) {
            $message = '<info>' . $message . '</info>';
        }
        $this->output->writeln($message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function error($message)
    {
        $width = $this->getApplication()->getTerminalDimensions()[0];
        $width = ($width) ?: 200;
        $width -= strlen($message);
        $width = max(0, $width);
        $subOne = false;
        if ($width % 2 !== 0) {
            $width += 1;
            $subOne = true;
        }
        $halfWidth = $width / 2;
        $message = str_repeat(' ', $halfWidth) . $message;
        if ($subOne) {
            $halfWidth -= 1;
        }
        $message .= str_repeat(' ', $halfWidth);
        $message = '<error>' . $message . '</error>';

        $this->output->writeln('<error>' . str_repeat(' ', $width + strlen($message)) . '</error>');
        $this->output->writeln($message);
        $this->output->writeln('<error>' . str_repeat(' ', $width + strlen($message)) . '</error>');
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function warning($message)
    {
        $width = $this->getApplication()->getTerminalDimensions()[0];
        $width = ($width) ?: 200;
        $width -= strlen($message);
        $subOne = false;
        if ($width % 2 !== 0) {
            $width += 1;
            $subOne = true;
        }
        $halfWidth = $width / 2;
        $message = str_repeat(' ', $halfWidth) . $message;
        if ($subOne) {
            $halfWidth -= 1;
        }
        $message .= str_repeat(' ', $halfWidth);
        $message = '<warning>' . $message . '</warning>';

        $style = new OutputFormatterStyle('black', 'yellow');
        $this->output->getFormatter()->setStyle('warning', $style);

        $this->output->writeln('<warning>' . str_repeat(' ', $width + strlen($message)) . '</warning>');
        $this->output->writeln($message);
        $this->output->writeln('<warning>' . str_repeat(' ', $width + strlen($message)) . '</warning>');
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function success($message)
    {
        $width = $this->getApplication()->getTerminalDimensions()[0];
        $width = ($width) ?: 200;
        $width -= strlen($message);
        $subOne = false;
        if ($width % 2 !== 0) {
            $width += 1;
            $subOne = true;
        }
        $halfWidth = $width / 2;
        $message = str_repeat(' ', $halfWidth) . $message;
        if ($subOne) {
            $halfWidth -= 1;
        }
        $message .= str_repeat(' ', $halfWidth);
        $message = '<success>' . $message . '</success>';

        $style = new OutputFormatterStyle('black', 'green');
        $this->output->getFormatter()->setStyle('success', $style);

        $this->output->writeln('<success>' . str_repeat(' ', $width + strlen($message)) . '</success>');
        $this->output->writeln($message);
        $this->output->writeln('<success>' . str_repeat(' ', $width + strlen($message)) . '</success>');
    }

    /**
     * @param string $question
     *
     * @return bool
     */
    public function askConfirmation($question)
    {
        $question = $question . '? <fg=green>[yes|no|abort]</fg=green> ';

        $result = $this->askAbortableConfirmation($this->output, $question, false);

        return $result;
    }

    /**
     * Asks a confirmation to the user.
     *
     * The question will be asked until the user answers by yes, or no.
     * If he answers nothing, it will use the default value. If he answers abort,
     * it will throw a RuntimeException.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output An Output instance
     * @param string $question The question to ask
     * @param bool $default The default answer if the user enters nothing
     *
     * @throws \RuntimeException
     *
     * @return bool true if the user has confirmed, false otherwise
     */
    public function askAbortableConfirmation(OutputInterface $output, $question, $default = true)
    {
        $answer = 'z';
        while ($answer && !in_array(strtolower($answer[0]), ['y', 'n', 'a'])) {
            $answer = $this->ask($question, $default);
        }

        if (strtolower($answer[0]) === 'a') {
            throw new RuntimeException('Aborted');
        }

        if ($default === false) {
            return $answer && strtolower($answer[0]) === 'y';
        }

        return !$answer || strtolower($answer[0]) === 'y';
    }

    /**
     * @param string $question
     * @param string|null $default
     *
     * @return string|null
     */
    public function ask($question, $default = null)
    {
        /** @var \Symfony\Component\Console\Helper\DialogHelper $dialog */
        $dialog = $this->getHelperSet()->get('dialog');

        return $dialog->ask($this->output, $question, $default);
    }

    /**
     * @param string $question
     * @param array $options
     * @param string $default
     *
     * @return mixed
     */
    public function select($question, array $options, $default)
    {
        /** @var \Symfony\Component\Console\Helper\DialogHelper $dialog */
        $dialog = $this->getHelperSet()->get('dialog');
        $selected = $dialog->select(
            $this->output,
            $question,
            $options,
            $default
        );

        return $options[$selected];
    }

    /**
     * @param bool $wrapInInfoTags
     *
     * @return void
     */
    public function printLineSeparator($wrapInInfoTags = true)
    {
        $width = $this->getApplication()->getTerminalDimensions()[0];
        $width = ($width) ?: 200;
        $this->info(str_repeat('-', $width), $wrapInInfoTags);
    }
}
