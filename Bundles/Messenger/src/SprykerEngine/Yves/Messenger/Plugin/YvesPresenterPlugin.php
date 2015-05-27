<?php

namespace SprykerEngine\Yves\Messenger\Plugin;

use Generated\Shared\Transfer\TranslatedMessageTransfer;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;
use SprykerEngine\Yves\Kernel\AbstractPlugin;
use Twig_Environment;
use SprykerFeature\Sdk\Glossary\Translator;

class YvesPresenterPlugin extends AbstractPlugin
{
    public function createYvesPresenter(
        MessengerInterface $messenger,
        Translator $translator,
        Twig_Environment $twig
    ){
        return $this->getDependencyContainer()->createYvesPresenter(
            $messenger,
            $translator,
            $twig
        );
    }
}
