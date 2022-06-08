<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile\Handler;

interface SessionAccountHandlerFileInterface
{
    /**
     * @param int $idAccount
     * @param string $idSession
     *
     * @return void
     */
    public function saveSessionAccount(int $idAccount, string $idSession): void;

    /**
     * @param int $idAccount
     * @param string $idSession
     *
     * @return bool
     */
    public function isSessionAccountValid(int $idAccount, string $idSession): bool;
}
