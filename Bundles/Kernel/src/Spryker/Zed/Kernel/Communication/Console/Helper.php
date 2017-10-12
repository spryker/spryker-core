<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication\Console;

use RuntimeException;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated Trait will be removed in next major version of Kernel
 */
trait Helper
{
    /**
     * @param string $message
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

    /**
     * @return \Silex\Application
     */
    abstract protected function getApplication();

    /**
     * @return \Symfony\Component\Console\Helper\HelperSet
     */
    abstract protected function getHelperSet();
}
