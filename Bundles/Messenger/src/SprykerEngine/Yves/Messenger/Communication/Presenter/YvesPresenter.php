<?php

namespace SprykerEngine\Yves\Messenger\Communication\Presenter;

use Generated\Shared\Transfer\TranslatedMessageTransfer;
use SprykerEngine\Shared\Messenger\Communication\Presenter\ObservingPresenterInterface;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;
use Twig_Environment;
use SprykerEngine\Shared\Messenger\Communication\Presenter\AbstractPresenter;
use SprykerFeature\Sdk\Glossary\Translator;

class YvesPresenter extends AbstractPresenter implements
    ObservingPresenterInterface
{

    /**
     * @var MessengerInterface
     */
    protected $messenger;

    /**
     * @param MessengerInterface $messenger
     * @param Translator $translator
     * @param Twig_Environment $twig
     */
    public function __construct(
        MessengerInterface $messenger,
        Translator $translator,
        Twig_Environment $twig
    ) {
        parent::__construct($messenger);

        $this->translator = $translator;
        $this->twig = $twig;

        $this->twig->addGlobal('messages', []);
        $this->messenger->registerPresenter($this);
    }

    public function display()
    {
        return $this->messenger->getAll();
    }

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
                        $message->getOptions()
                    )
                );

            $messages[] = $translatedMessage;
        }

        $this->twig->addGlobal('messages', $messages);
    }

}
