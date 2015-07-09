<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Messenger\Communication\Plugin;

use SprykerEngine\Shared\Messenger\Business\Model\MessengerInterface;

abstract class AbstractTwigMessengerExtension extends \Twig_Extension
{

    /**
     * @var MessengerInterface
     */
    protected $messenger;

    /**
     * @param MessengerInterface $messenger
     *
     * @return \Twig_ExtensionInterface
     */
    public function setMessenger(MessengerInterface $messenger)
    {
        $this->messenger = $messenger;

        return $this;
    }

    /**
     * @return MessengerInterface
     */
    public function getMessenger()
    {
        return $this->messenger;
    }

    /**
     * Returns a list of global variables to add to the existing list.
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return [
            'messages' => $this->messenger->getAll(),
        ];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'TwigMessengerPlugin';
    }

}
