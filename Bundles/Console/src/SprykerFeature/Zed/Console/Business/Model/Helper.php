<?php

namespace SprykerFeature\Zed\Console\Business\Model;

use Silex\Application;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\HelperSet;

trait Helper
{

    /**
     * @param string $message
     * @param bool $wrapInInfoTags
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
     */
    public function askConfirmation($question)
    {
        $question = $question . '? <fg=green>[yes|no]</fg=green> ';
        $dialog = $this->getHelperSet()->get('dialog');

        return $dialog->askConfirmation($this->output, $question, false);
    }

    /**
     * @param string $question
     * @param null $default
     *
     * @return mixed
     */
    public function ask($question, $default = null)
    {
        $dialog = $this->getHelperSet()->get('dialog');

        return $dialog->ask($this->output, $question, $default);
    }

    /**
     * @param string $question
     * @param array $options
     * @param $default
     *
     * @return mixed
     */
    public function select($question, array $options, $default)
    {
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
     *
     */
    public function printLineSeparator($wrapInInfoTags = true)
    {
        $width = $this->getApplication()->getTerminalDimensions()[0];
        $width = ($width) ?: 200;
        $this->info(str_repeat('-', $width), $wrapInInfoTags);
    }

    /**
     * @return Application
     */
    abstract protected function getApplication();

    /**
     * @return HelperSet
     */
    abstract protected function getHelperSet();

}
