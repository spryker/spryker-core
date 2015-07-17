<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

/**
 * Flash-Messenger
 *
 * Usage in Controller (or somewhere else):
 * <code>Pal_FlashMessage::getInstance()->addNotification('Hello World');</code>
 *
 * Retrieve messages in layout:
 * <code>Pal_FlashMessage::getInstance()->getNotifications()</code>
 *
 * Available Message-Types:
 * - Notification
 * - Success
 * - Error
 */
class SprykerFeature_Zed_Library_FlashMessage
{

    /**
     * @var array
     */
    protected $types = ['error', 'success', 'notification'];

    /**
     * Singleton
     *
     * @var \SprykerFeature_Zed_Library_FlashMessage
     */
    protected static $instance;

    /**
     * @var Zend_Session_Namespace
     */
    protected $session;

    protected function __construct()
    {
        $this->session = new Zend_Session_Namespace(__CLASS__);
    }

    /**
     * @static
     *
     * @return \SprykerFeature_Zed_Library_FlashMessage
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new \SprykerFeature_Zed_Library_FlashMessage();
        }

        return self::$instance;
    }

    /**
     * @param $message
     */
    public function addSuccess($message)
    {
        $this->addMessage($message, 'success');
    }

    /**
     * @param $message
     */
    public function addNotification($message)
    {
        $this->addMessage($message, 'notification');
    }

    /**
     * @param $message
     */
    public function addError($message)
    {
        $this->addMessage($message, 'error');
    }

    /**
     * @return array
     */
    public function getSuccesses()
    {
        return $this->getMessages('success');
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->getMessages('error');
    }

    /**
     * @return array
     */
    public function getNotifications()
    {
        return $this->getMessages('notification');
    }

    /**
     * Adds message to session
     *
     * @param $message
     * @param $type
     */
    protected function addMessage($message, $type)
    {
        assert(in_array($type, $this->types));
        assert(is_string($message));

        if (is_null($this->session->$type) || false === is_array($this->session->$type)) {
            $this->session->$type = [];
        }

        $messages = $this->session->$type;
        $messages[] = $message;
        $this->session->$type = $messages;
    }

    /**
     * Returns message and removes it from the session
     *
     * @param $type
     * @param bool $removeFromSession
     *
     * @return array
     */
    protected function getMessages($type, $removeFromSession = true)
    {
        assert(in_array($type, $this->types));

        if (isset($this->session->$type) && is_array($this->session->$type)) {
            $message = $this->session->$type;
            if ($removeFromSession) {
                unset($this->session->$type);
            }

            return $message;
        } else {
            return [];
        }
    }

}
