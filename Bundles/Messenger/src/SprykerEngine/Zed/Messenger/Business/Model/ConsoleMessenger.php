<?php

namespace SprykerEngine\Zed\Messenger\Business\Model;

use SprykerEngine\Shared\Messenger\Business\Model\AbstractMessenger;
use Symfony\Component\Console\Output\OutputInterface;
use SprykerEngine\Zed\Translation\Business\TranslationFacade;

class ConsoleMessenger extends AbstractMessenger
{

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var TranslationFacade
     */
    private $translator;

    /**
     * @param OutputInterface $output
     */
    function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param TranslationFacade $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $type
     * @param string $message
     * @param array $options
     *
     * @return $this
     * @throws \SprykerEngine\Shared\Messenger\Business\Model\Exception\MessageTypeNotFoundException
     */
    public function add($type, $message, array $options = [])
    {
        parent::add($type, $message, $options);

        $this->display();

        return $this;
    }

    /**
     * @param string $message
     * @param array $options
     *
     * @return string
     */
    private function translate($message, array $options = [])
    {
        if (!is_null($this->translator)) {
            $message = $this->translator->translate(
                $message,
                $options
            );
        }

        return $message;
    }

    private function display()
    {
        foreach ($this->getAll() as $message) {
            $displayedMessage = $this->translator->translate(
                $message->getMessage(),
                $message->getOptions()
            );

            $this->output->writeln($displayedMessage);
        }
    }

}
