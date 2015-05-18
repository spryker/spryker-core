<?php

namespace SprykerFeature\Zed\Application\Business\Model\Messenger\Presenter;

use SprykerEngine\Zed\Translation\Business\TranslationFacade;
use Generated\Shared\Transfer\LocaleTransfer;
use Symfony\Component\Console\Output\OutputInterface;
use SprykerFeature\Zed\Application\Business\Model\Messenger\MessengerInterface;

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
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param MessengerInterface $messenger
     * @param TranslationFacade $translator
     * @param LocaleTransfer $locale
     * @param OutputInterface $output
     */
    public function __construct(
        MessengerInterface $messenger,
        TranslationFacade $translator,
        LocaleTransfer $locale,
        OutputInterface $output
    ) {
        parent::__construct($messenger);

        $this->translator = $translator;
        $this->locale = $locale;
        $this->output = $output;

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
                $this->locale
            );

            $this->output->writeln($displayedMessage);
        }
    }
}