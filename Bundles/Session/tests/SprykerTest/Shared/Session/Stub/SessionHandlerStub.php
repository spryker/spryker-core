<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Session\Stub;

use SessionHandlerInterface;

class SessionHandlerStub implements SessionHandlerInterface
{
    /**
     * @return bool
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * @param string $session_id
     *
     * @return bool
     */
    public function destroy($session_id): bool
    {
        return true;
    }

    /**
     * @param int $maxlifetime
     *
     * @return bool
     */
    public function gc($maxlifetime): bool
    {
        return true;
    }

    /**
     * @param string $save_path
     * @param string $name
     *
     * @return bool
     */
    public function open($save_path, $name): bool
    {
        return true;
    }

    /**
     * @param string $session_id
     *
     * @return string
     */
    public function read($session_id): string
    {
        return '';
    }

    /**
     * @param string $session_id
     * @param string $session_data
     *
     * @return bool
     */
    public function write($session_id, $session_data): bool
    {
        return true;
    }
}
