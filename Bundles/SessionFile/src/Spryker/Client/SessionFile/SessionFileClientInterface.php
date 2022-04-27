<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SessionFile;

interface SessionFileClientInterface
{
    /**
     * Specification:
     * - Saves ID session for customer.
     *
     * @api
     *
     * @param int $idCustomer
     * @param string $idSession
     *
     * @return void
     */
    public function saveSessionCustomer(int $idCustomer, string $idSession): void;

    /**
     * Specification:
     * - Retrieves ID session by `idCustomer` from file and compare with current.
     * - Returns true if ID session is valid.
     *
     * @api
     *
     * @param int $idCustomer
     * @param string $idSession
     *
     * @return bool
     */
    public function isSessionCustomerValid(int $idCustomer, string $idSession): bool;
}
