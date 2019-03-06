<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionStorage implements StorageInterface
{
    public const SESSION_KEY_PREFIX = 'req_';

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @var string
     */
    protected $keyPrefix = self::SESSION_KEY_PREFIX;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $formName
     *
     * @return string
     */
    public function getToken(string $formName): string
    {
        return $this->session->get($this->keyPrefix . $formName);
    }

    /**
     * @param string $formName
     *
     * @return void
     */
    public function deleteToken(string $formName): void
    {
        $this->session->remove($this->keyPrefix . $formName);
    }

    /**
     * @param string $formName
     * @param string $token
     *
     * @return void
     */
    public function setToken(string $formName, string $token): void
    {
        $this->session->set($this->keyPrefix . $formName, $token);
    }
}
