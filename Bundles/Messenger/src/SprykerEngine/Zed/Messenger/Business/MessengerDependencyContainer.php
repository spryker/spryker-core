<?php

namespace SprykerEngine\Zed\Messenger\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;
use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Translation\Business\TranslationFacade;
use Twig_Environment;
use Symfony\Component\Console\Output\ConsoleOutput;

class MessengerDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return MessengerInterface
     */
    public function getMessenger()
    {
        return $this->getFactory()->createModelMessenger();
    }

    public function createZedPresenter(
        MessengerInterface $messenger,
        TranslationFacade $translator,
        LocaleTransfer $locale,
        Twig_Environment $twig
    ) {
        return $this->getFactory()->createPresenterZedPresenter(
            $messenger,
            $translator,
            $locale,
            $twig
        );
    }

    /**
     * @param MessengerInterface $messenger
     * @param TranslationFacade $translator
     * @param LocaleTransfer $locale
     * @param ConsoleOutput $output
     *
     * @return ConsolePresenter
     */
    public function createConsolePresenter(
        MessengerInterface $messenger,
        TranslationFacade $translator,
        LocaleTransfer $locale,
        ConsoleOutput $output
    ) {
        return $this->getFactory()->createPresenterConsolePresenter(
            $messenger,
            $translator,
            $locale,
            $output
        );
    }

}
