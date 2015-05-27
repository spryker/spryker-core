<?php

namespace SprykerEngine\Zed\Messenger\Communication\Presenter;

use Generated\Shared\Transfer\TranslatedMessageTransfer;
use SprykerEngine\Shared\Messenger\Communication\Presenter\ObservingPresenterInterface;
use SprykerEngine\Zed\Translation\Business\TranslationFacade;
use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;
use Twig_Environment;
use SprykerEngine\Shared\Messenger\Communication\Presenter\AbstractPresenter;

class ZedPresenter extends AbstractPresenter implements
    ObservingPresenterInterface
{
    /**
     * @var MessengerInterface
     */
    protected $messenger;

    /**
     * @param MessengerInterface $messenger
     * @param TranslationFacade $translator
     * @param LocaleTransfer $locale
     * @param Twig_Environment $twig
     */
    public function __construct(
        MessengerInterface $messenger,
        TranslationFacade $translator,
        LocaleTransfer $locale,
        Twig_Environment $twig
    ) {
        parent::__construct($messenger);

        $this->translator = $translator;
        $this->locale = $locale;
        $this->twig = $twig;

        $this->twig->addGlobal('messages', []);
        $this->messenger->registerPresenter($this);
    }

    /**
     * @return \SprykerEngine\Shared\Messenger\Business\Model\Message\MessageInterface[]
     */
    public function display()
    {
        return $this->messenger->getAll();
    }

    /**
     *
     */
    public function update()
    {
        $messages = $this->twig->getGlobals()['messages'];

        if ($messages === null) {
            $messages = [];
        }

        foreach ($this->messenger->getAll() as $message) {
            $translatedMessage = new TranslatedMessageTransfer();

            $translatedMessage
                ->setType($message->getType())
                ->setMessage(
                    $this->translator->translate(
                        $message->getMessage(),
                        $message->getOptions(),
                        null,
                        $this->locale
                    )
                );

            $messages[] = $translatedMessage;
        }

        $this->twig->addGlobal('messages', $messages);
    }

}
