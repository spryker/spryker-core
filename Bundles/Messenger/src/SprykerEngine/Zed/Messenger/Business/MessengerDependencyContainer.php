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

    /**
     * @return MessengerInterface
     */
    public function getConsoleMessenger()
    {
        return $this->getFactory()->createModelConsoleMessenger();
    }

}
