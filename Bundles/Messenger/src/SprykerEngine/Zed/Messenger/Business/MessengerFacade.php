<?php

namespace SprykerEngine\Zed\Messenger\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Shared\Messenger\Business\Model\Exception\MessageTypeNotFoundException;
use SprykerEngine\Shared\Messenger\Business\Model\Message\MessageInterface;
use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;
use SprykerEngine\Shared\Messenger\Communication\Presenter\ObservingPresenterInterface;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Translation\Business\TranslationFacade;
use Generated\Shared\Transfer\LocaleTransfer;
use Twig_Environment;
use SprykerEngine\Zed\Messenger\Business\Presenter\ZedPresenter;
use SprykerEngine\Zed\Messenger\Business\Presenter\ConsolePresenter;
use Symfony\Component\Console\Output\ConsoleOutput;

class MessengerFacade extends AbstractFacade implements MessengerInterface
{

    /**
     * @var MessengerInterface
     */
    protected $messenger;

    /**
     * @param FactoryInterface $factory
     * @param Locator $locator
     */
    public function __construct(FactoryInterface $factory, Locator $locator)
    {
        parent::__construct($factory, $locator);

        $this->messenger = $this->getDependencyContainer()->getMessenger();
    }

    /**
     * @param string $type
     *
     * @return mixed
     */
    public function getAll($type = null)
    {
        return $this->messenger->getAll($type);
    }

    /**
     * @param string $type
     * @param string $message
     * @param array $options
     *
     * @return MessengerInterface
     * @throws MessageTypeNotFoundException
     */
    public function add($type, $message, array $options = [])
    {
        return $this->messenger->add(
            $type,
            $message,
            $options
        );
    }

    /**
     * @param string $type
     *
     * @return MessageInterface
     */
    public function get($type = null)
    {
        return $this->messenger->get($type);
    }

    /**
     * @param ObservingPresenterInterface $presenter
     *
     * @return MessengerInterface
     */
    public function registerPresenter(ObservingPresenterInterface $presenter)
    {
        return $this->messenger->registerPresenter(
            $presenter
        );
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     *
     * @return MessengerInterface
     */
    public function alert($message, array $context = [])
    {
        return $this->messenger->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     *
     * @return MessengerInterface
     */
    public function critical($message, array $context = [])
    {
        return $this->messenger->critical($message, $context);
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     *
     * @return MessengerInterface
     */
    public function emergency($message, array $context = [])
    {
        return $this->messenger->emergency($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     *
     * @return MessengerInterface
     */
    public function error($message, array $context = [])
    {
        return $this->messenger->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     *
     * @return MessengerInterface
     */
    public function warning($message, array $context = [])
    {
        return $this->messenger->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     *
     * @return MessengerInterface
     */
    public function notice($message, array $context = [])
    {
        return $this->messenger->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     *
     * @return MessengerInterface
     */
    public function info($message, array $context = [])
    {
        return $this->messenger->info($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     *
     * @return MessengerInterface
     */
    public function success($message, array $context = [])
    {
        return $this->messenger->success($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     *
     * @return MessengerInterface
     */
    public function debug($message, array $context = [])
    {
        return $this->messenger->debug($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @return MessengerInterface
     */
    public function log($level, $message, array $context = [])
    {
        return $this->messenger->log($level, $message, $context);
    }

    /**
     * @param MessengerInterface $messenger
     * @param TranslationFacade $translator
     * @param LocaleTransfer $locale
     * @param Twig_Environment $twig
     *
     * @return ZedPresenter
     */
    public function createZedPresenter(
        MessengerInterface $messenger,
        TranslationFacade $translator,
        LocaleTransfer $locale,
        Twig_Environment $twig
    ) {
        return $this->getDependencyContainer()->createZedPresenter(
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
        return $this->getDependencyContainer()->createConsolePresenter(
            $messenger,
            $translator,
            $locale,
            $output
        );
    }

}
