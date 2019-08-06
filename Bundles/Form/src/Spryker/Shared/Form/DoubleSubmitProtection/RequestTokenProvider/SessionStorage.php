<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionStorage implements StorageInterface
{
    protected const SESSION_KEY_PREFIX = 'req_';

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

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
     * @return string|null
     */
    public function getToken(string $formName): ?string
    {
        return $this->session->get($this->buildSessionKey($formName));
    }

    /**
     * @param string $formName
     *
     * @return void
     */
    public function deleteToken(string $formName): void
    {
        $this->session->remove($this->buildSessionKey($formName));
    }

    /**
     * @param string $formName
     * @param string $token
     *
     * @return void
     */
    public function setToken(string $formName, string $token): void
    {
        $this->session->set($this->buildSessionKey($formName), $token);
    }

    /**
     * @param string $formName
     *
     * @return string
     */
    protected function buildSessionKey(string $formName): string
    {
        return sprintf('%s%s', static::SESSION_KEY_PREFIX, $formName);
    }
}
