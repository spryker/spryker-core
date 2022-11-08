<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionRedis\Handler\KeyBuilder;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;

class SessionKeyBuilder implements SessionKeyBuilderInterface
{
    /**
     * @var string
     */
    protected const SESSION_KEY_PREFIX = 'session';

    /**
     * @var string
     */
    protected const SESSION_LOCK_KEY_SUFFIX = 'lock';

    /**
     * @var string
     */
    protected const SESSION_ACCOUNT_KEY_SUFFIX = 'account';

    /**
     * @var string
     */
    protected const SESSION_ENTITY_KEY_SUFFIX = 'entity';

    /**
     * By default generated session key has length = 32 symbols, if this const will be less than 32 there can be session collision.
     *
     * @var int
     */
    protected const MAX_SESSION_KEY_LENGTH = 64;

    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function buildSessionKey(string $sessionId): string
    {
        if (mb_strlen($sessionId) > static::MAX_SESSION_KEY_LENGTH) {
            $sessionId = mb_substr($sessionId, 0, static::MAX_SESSION_KEY_LENGTH);
        }

        return sprintf('%s:%s', static::SESSION_KEY_PREFIX, $sessionId);
    }

    /**
     * @param string $sessionId
     *
     * @return string
     */
    public function buildLockKey(string $sessionId): string
    {
        return sprintf('%s:%s', $this->buildSessionKey($sessionId), static::SESSION_LOCK_KEY_SUFFIX);
    }

    /**
     * @param string $accountType
     * @param string $idAccount
     *
     * @return string
     */
    public function buildAccountKey(string $accountType, string $idAccount): string
    {
        return sprintf('%s:%s:%s', $idAccount, $accountType, static::SESSION_ACCOUNT_KEY_SUFFIX);
    }

    /**
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return string
     */
    public function buildEntityKey(SessionEntityRequestTransfer $sessionEntityRequestTransfer): string
    {
        return sprintf(
            '%s:%s:%s',
            $sessionEntityRequestTransfer->getIdEntityOrFail(),
            $sessionEntityRequestTransfer->getEntityTypeOrFail(),
            static::SESSION_ENTITY_KEY_SUFFIX,
        );
    }
}
