<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Console;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Terminal;

/**
 * @method \Symfony\Component\Console\Application getApplication()
 */
class Console extends SymfonyCommand
{
    public const CODE_SUCCESS = 0;
    public const CODE_ERROR = 1;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $messenger;

    /**
     * @var int
     */
    protected $exitCode = self::CODE_SUCCESS;

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @return bool
     */
    protected function hasError(): bool
    {
        return $this->exitCode !== static::CODE_SUCCESS;
    }

    /**
     * @return int
     */
    protected function getLastExitCode(): int
    {
        return $this->exitCode;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function getMessenger(): LoggerInterface
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
    public function info($message, bool $wrapInInfoTags = true): void
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
    public function error(string $message): void
    {
        $width = $this->getTerminalWidth() - mb_strlen($message) - 1;
        $width = max(0, $width);
        $message .= str_repeat(' ', $width);

        $this->output->writeln(sprintf('<error> %s</error>', $message));
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function warning(string $message): void
    {
        $style = new OutputFormatterStyle('black', 'yellow');
        $this->output->getFormatter()->setStyle('warning', $style);

        $width = $this->getTerminalWidth() - mb_strlen($message) - 1;
        $width = max(0, $width);
        $message .= str_repeat(' ', $width);

        $this->output->writeln(sprintf('<warning> %s</warning>', $message));
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function success(string $message): void
    {
        $style = new OutputFormatterStyle('black', 'green');
        $this->output->getFormatter()->setStyle('success', $style);

        $width = $this->getTerminalWidth() - mb_strlen($message) - 1;
        $width = max(0, $width);
        $message .= str_repeat(' ', $width);

        $this->output->writeln(sprintf('<success> %s</success>', $message));
    }

    /**
     * @param string $question
     * @param string|null $default
     *
     * @return string|null
     */
    public function ask(string $question, ?string $default): ?string
    {
        $questionHelper = $this->getQuestionHelper();
        $question = new Question($question, $default);

        return $questionHelper->ask($this->input, $this->output, $question);
    }

    /**
     * @param string $question
     * @param array $options
     * @param string $default
     *
     * @return mixed
     */
    public function select(string $question, array $options, string $default)
    {
        $questionHelper = $this->getQuestionHelper();

        $choiceQuestion = new ChoiceQuestion($question, $options, $default);

        return $questionHelper->ask($this->input, $this->output, $choiceQuestion);
    }

    /**
     * @return \Symfony\Component\Console\Helper\QuestionHelper
     */
    protected function getQuestionHelper(): HelperInterface
    {
        /** @var \Symfony\Component\Console\Helper\QuestionHelper $questionHelper */
        $questionHelper = $this->getHelperSet()->get('question');

        return $questionHelper;
    }

    /**
     * @param bool $wrapInInfoTags
     *
     * @return void
     */
    public function printLineSeparator(bool $wrapInInfoTags = true): void
    {
        $width = $this->getTerminalWidth();
        $this->info(str_repeat('-', $width), $wrapInInfoTags);
    }

    /**
     * @return int
     */
    protected function getTerminalWidth(): int
    {
        $terminal = new Terminal();
        $width = $terminal->getWidth();

        return $width;
    }
}
