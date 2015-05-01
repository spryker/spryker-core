<?php

namespace SprykerFeature\Yves\Library\Session;

use SprykerFeature\Shared\Library\Communication\Message;
use SprykerFeature\Shared\ZedRequest\Client\ResponseInterface;
use Symfony\Component\Translation\TranslatorInterface;
use \Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

/**
 * TODO move to Yves Package
 */
class FlashMessageHelper
{
    const SUCCESS = 'success';
    const ERROR = 'error';
    const WARNING = 'warning';
    const INFO = 'info';

    /**
     * @var FlashBag
     */
    protected $flashBag;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param FlashBag            $flashBag
     * @param TranslatorInterface $translator
     */
    public function __construct(FlashBag $flashBag, TranslatorInterface $translator)
    {
        $this->flashBag = $flashBag;
        $this->translator = $translator;
    }

    /**
     * @param string         $type
     * @param Message|string $message
     * @param array          $params  Translation Parameters
     */
    public function addMessage($type, $message, $params = [])
    {
        if ($message instanceof Message) {
            $params = $message->getData();
            $messageString = $message->getMessage();
            $this->doAddMessage($type, $messageString, $params);
        } else {
            $this->doAddMessage($type, $message, $params);
        }
    }

    /**
     * @param string $type
     * @param string $message
     *
     * @return bool
     */
    public function hasMessage($type, $message)
    {
        foreach ($this->flashBag->get($type) as $flashMessage) {
            if ($message === $flashMessage) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param ResponseInterface $transferResponse
     */
    public function addMessagesFromResponse(ResponseInterface $transferResponse)
    {
        foreach ($transferResponse->getErrorMessages() as $message) {
            $this->addMessage(self::ERROR, $message);
        }
        foreach ($transferResponse->getMessages() as $message) {
            $this->addMessage(self::SUCCESS, $message);
        }
    }

    /**
     * @param string $type
     * @param string $message
     * @param array $params
     */
    protected function doAddMessage($type, $message, array $params = [])
    {
        if (!$this->hasMessage($type, $message)) {
            $this->flashBag->add($type, $message);
        }
    }
}
