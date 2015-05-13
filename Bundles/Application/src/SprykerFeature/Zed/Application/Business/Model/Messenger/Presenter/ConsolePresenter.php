<?php

namespace SprykerFeature\Zed\Application\Business\Model\Messenger;

use SprykerEngine\Zed\Translation\Business\TranslationFacade;
use Generated\Shared\Transfer\LocaleTransfer;

class ConsolePresenter extends AbstractPresenter implements
    ObservingPresenterInterface
{
    /**
     * @var TranslationFacade
     */
    protected $translator;

    /**
     * @var LocaleTransfer
     */
    protected $locale;

    /**
     * @param MessengerInterface $messenger
     * @param TranslationFacade $translator
     * @param LocaleTransfer $locale
     */
    public function __construct(
        MessengerInterface $messenger,
        TranslationFacade $translator,
        LocaleTransfer $locale
    ) {
        parent::__construct($messenger);

        $this->translator = $translator;
        $this->locale = $locale;

        $this->messenger->registerPresenter($this);
    }

    public function update()
    {
        $this->display();
    }

    public function display()
    {
        foreach ($this->messenger->getAll() as $message) {
            $displayedMessage = $this->translator->translate(
                $message->getMessage(),
                $message->getOptions(),
                null,
                $this->locale->getLocaleName()
            );
        }
    }
}