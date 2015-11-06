<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Session\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;

class SessionClient extends AbstractClient implements SessionClientInterface
{

    /**
     * @var SessionInterface
     */
    protected static $container = null;

    /**
     * @param SessionInterface $container
     */
    public function setContainer(SessionInterface $container)
    {
        self::$container = $container;
    }

    /**
     * @return SessionInterface
     */
    protected function getContainer()
    {
        return self::$container;
    }

    /**
     * Returns an attribute.
     *
     * @param string $name The attribute name
     * @param mixed $default The default value if not found.
     *
     * @return mixed
     *
     * @api
     */
    public function get($name, $default = null)
    {
        return $this->getContainer()->get($name, $default);
    }

    /**
     * Sets an attribute.
     *
     * @param string $name
     * @param mixed $value
     *
     * @api
     */
    public function set($name, $value)
    {
        return $this->getContainer()->set($name, $value);
    }

    /**
     * Removes an attribute.
     *
     * @param string $name
     *
     * @return mixed The removed value or null when it does not exist
     *
     * @api
     */
    public function remove($name)
    {
        return $this->getContainer()->remove($name);
    }

    /**
     * Starts the session storage.
     *
     * @throws \RuntimeException If session fails to start.
     *
     * @return bool True if session started.
     *
     * @api
     */
    public function start()
    {
        return $this->getContainer()->start();
    }

    /**
     * Returns the session ID.
     *
     * @return string The session ID.
     *
     * @api
     */
    public function getId()
    {
        return $this->getContainer()->getId();
    }

    /**
     * Sets the session ID.
     *
     * @param string $id
     *
     * @api
     */
    public function setId($id)
    {
        return $this->getContainer()->setId($id);
    }

    /**
     * Returns the session name.
     *
     * @return mixed The session name.
     *
     * @api
     */
    public function getName()
    {
        return $this->getContainer()->getName();
    }

    /**
     * Sets the session name.
     *
     * @param string $name
     *
     * @api
     */
    public function setName($name)
    {
        return $this->getContainer()->setName($name);
    }

    /**
     * Invalidates the current session.
     *
     * Clears all session attributes and flashes and regenerates the
     * session and deletes the old session from persistence.
     *
     * @param int $lifetime Sets the cookie lifetime for the session cookie. A null value
     *   will leave the system settings unchanged, 0 sets the cookie
     *   to expire with browser session. Time is in seconds, and is
     *   not a Unix timestamp.
     *
     * @return bool True if session invalidated, false if error.
     *
     * @api
     */
    public function invalidate($lifetime = null)
    {
        return $this->getContainer()->invalidate($lifetime);
    }

    /**
     * Migrates the current session to a new session id while maintaining all
     * session attributes.
     *
     * @param bool $destroy Whether to delete the old session or leave it to garbage collection.
     * @param int $lifetime Sets the cookie lifetime for the session cookie. A null value
     *   will leave the system settings unchanged, 0 sets the cookie
     *   to expire with browser session. Time is in seconds, and is
     *   not a Unix timestamp.
     *
     * @return bool True if session migrated, false if error.
     *
     * @api
     */
    public function migrate($destroy = false, $lifetime = null)
    {
        return $this->getContainer()->migrate($destroy, $lifetime);
    }

    /**
     * Force the session to be saved and closed.
     *
     * This method is generally not required for real sessions as
     * the session will be automatically saved at the end of
     * code execution.
     */
    public function save()
    {
        return $this->getContainer()->save();
    }

    /**
     * Checks if an attribute is defined.
     *
     * @param string $name The attribute name
     *
     * @return bool true if the attribute is defined, false otherwise
     *
     * @api
     */
    public function has($name)
    {
        return $this->getContainer()->has($name);
    }

    /**
     * Returns attributes.
     *
     * @return array Attributes
     *
     * @api
     */
    public function all()
    {
        return $this->getContainer()->all();
    }

    /**
     * Sets attributes.
     *
     * @param array $attributes Attributes
     */
    public function replace(array $attributes)
    {
        return $this->getContainer()->replace($attributes);
    }

    /**
     * Clears all attributes.
     *
     * @api
     */
    public function clear()
    {
        return $this->getContainer()->clear();
    }

    /**
     * Checks if the session was started.
     *
     * @return bool
     */
    public function isStarted()
    {
        return $this->getContainer()->isStarted();
    }

    /**
     * Registers a SessionBagInterface with the session.
     *
     * @param SessionBagInterface $bag
     */
    public function registerBag(SessionBagInterface $bag)
    {
        return $this->getContainer()->registerBag($bag);
    }

    /**
     * Gets a bag instance by name.
     *
     * @param string $name
     *
     * @return SessionBagInterface
     */
    public function getBag($name)
    {
        return $this->getContainer()->getBag($name);
    }

    /**
     * Gets session meta.
     *
     * @return MetadataBag
     */
    public function getMetadataBag()
    {
        return $this->getContainer()->getMetadataBag();
    }

}
