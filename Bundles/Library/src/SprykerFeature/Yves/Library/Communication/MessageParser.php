<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Library\Communication;

use SprykerFeature\Shared\Library\Communication\Message;
use Symfony\Component\Translation\TranslatorInterface;

class MessageParser
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Message[] $messages
     *
     * @return string[]
     */
    public function parseAndTranslateMessages(array $messages)
    {
        $parsedMessages = [];
        foreach ($messages as $message) {
            $parsedMessages[] = $this->parseAndTranslateMessage($message);
        }

        return $parsedMessages;
    }

    /**
     * @param Message $message
     *
     * @return string
     */
    public function parseAndTranslateMessage(Message $message)
    {
        return $this->translator->trans($message->getMessage(), $message->getData());
    }
}
