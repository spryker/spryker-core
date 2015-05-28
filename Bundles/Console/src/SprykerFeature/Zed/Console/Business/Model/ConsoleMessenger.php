<?php

namespace SprykerFeature\Zed\Console\Business\Model;

use SprykerEngine\Shared\Translation\TranslationInterface;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class ConsoleMessenger extends ConsoleLogger implements MessengerInterface
{
    /**
     * @var TranslationInterface
     */
    private $translator;

    /**
     * @param TranslationInterface $translator
     */
    public function setTranslator(TranslationInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param string $message
     * @param array $context
     *
     * @return string
     */
    private function translate($message, array $context = [])
    {
        if (!is_null($this->translator)) {
            $message = $this->translator->translate($message, $context);
        }

        return $message;
    }

    public function log($level, $message, array $context = array())
    {
        $message = $this->translate($message, $context);

        parent::log($level, $message, $context);
    }

}
