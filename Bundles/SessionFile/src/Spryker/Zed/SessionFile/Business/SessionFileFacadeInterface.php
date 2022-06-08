<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionFile\Business;

interface SessionFileFacadeInterface
{
    /**
     * Specification:
     * - Saves ID session for user.
     *
     * @api
     *
     * @param int $idUser
     * @param string $idSession
     *
     * @return void
     */
    public function saveSessionUser(int $idUser, string $idSession): void;

    /**
     * Specification:
     * - Retrieves ID session by `idUser` from file and compare with current.
     * - Returns true if ID session is valid.
     *
     * @api
     *
     * @param int $idUser
     * @param string $idSession
     *
     * @return bool
     */
    public function isSessionUserValid(int $idUser, string $idSession): bool;
}
