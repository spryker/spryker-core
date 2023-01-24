<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Session;

use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface SessionClientInterface
{
    /**
     * Specification:
     * - Sets the container for the session.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $container
     *
     * @return void
     */
    public function setContainer(SessionInterface $container);

    /**
     * Specification:
     *  - Starts the session storage.
     *
     * @api
     *
     * @throws \RuntimeException if session fails to start
     *
     * @return bool
     */
    public function start();

    /**
     * Specification:
     * - Returns the session ID.
     *
     * @api
     *
     * @return string
     */
    public function getId();

    /**
     * Specification:
     * - Sets the session ID.
     *
     * @api
     *
     * @param string $id
     *
     * @return void
     */
    public function setId(string $id);

    /**
     * Specification:
     * - Returns the session name.
     *
     * @api
     *
     * @return string
     */
    public function getName();

    /**
     * Specification:
     * - Sets the session name.
     *
     * @api
     *
     * @param string $name
     *
     * @return void
     */
    public function setName(string $name);

    /**
     * Specification:
     * - Invalidates the current session.
     * - Clears all session attributes and flashes and regenerates the
     * session and deletes the old session from persistence.
     * - Sets the cookie lifetime for the session cookie. A null value
     * will leave the system settings unchanged, 0 sets the cookie
     * to expire with browser session. Time is in seconds, and is
     * not a Unix timestamp.
     *
     * @api
     *
     * @param int|null $lifetime
     *
     * @return bool
     */
    public function invalidate(?int $lifetime = null);

    /**
     * Specification:
     * - Migrates the current session to a new session id while maintaining all
     * session attributes.
     * - Whether to delete the old session or leave it to garbage collection
     * - Sets the cookie lifetime for the session cookie. A null value
     * will leave the system settings unchanged, 0 sets the cookie
     * to expire with browser session. Time is in seconds, and is
     * not a Unix timestamp.
     *
     * @api
     *
     * @param bool $destroy
     * @param int|null $lifetime
     *
     * @return bool
     */
    public function migrate(bool $destroy = false, ?int $lifetime = null);

    /**
     * Specification:
     * - Force the session to be saved and closed.
     * This method is generally not required for real sessions as
     * the session will be automatically saved at the end of
     * code execution.
     *
     * @api
     *
     * @return void
     */
    public function save();

    /**
     * Specification:
     * - Checks if an attribute is defined.
     *
     * @api
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name);

    /**
     * Specification:
     * - Returns an attribute.
     *
     * @api
     *
     * @param string $name
     * @param mixed $default The default value if not found
     *
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * Specification:
     * - Sets an attribute.
     *
     * @api
     *
     * @param string $name
     * @param mixed $value
     *
     * @return mixed
     */
    public function set(string $name, $value);

    /**
     * Specification:
     * - Returns attributes.
     *
     * @api
     *
     * @return array
     */
    public function all();

    /**
     * Specification:
     * - Sets attributes.
     *
     * @api
     *
     * @param array $attributes
     *
     * @return void
     */
    public function replace(array $attributes);

    /**
     * Specification:
     * - Removes an attribute.
     *
     * @api
     *
     * @param string $name
     *
     * @return mixed
     */
    public function remove(string $name);

    /**
     * Specification:
     * - Clears all attributes.
     *
     * @api
     *
     * @return void
     */
    public function clear();

    /**
     * Specification:
     * - Checks if the session was started.
     *
     * @api
     *
     * @return bool
     */
    public function isStarted();

    /**
     * Specification:
     * - Registers a SessionBagInterface with the session.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionBagInterface $bag
     *
     * @return void
     */
    public function registerBag(SessionBagInterface $bag);

    /**
     * Specification:
     * - Gets a bag instance by name.
     *
     * @api
     *
     * @param string $name
     *
     * @return \Symfony\Component\HttpFoundation\Session\SessionBagInterface
     */
    public function getBag(string $name);

    /**
     * Specification:
     * - Gets session meta.
     *
     * @api
     *
     * @return \Symfony\Component\HttpFoundation\Session\Storage\MetadataBag
     */
    public function getMetadataBag();
}
