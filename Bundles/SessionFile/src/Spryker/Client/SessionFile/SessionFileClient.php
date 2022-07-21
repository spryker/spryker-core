<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SessionFile;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SessionFile\SessionFileFactory getFactory()
 */
class SessionFileClient extends AbstractClient implements SessionFileClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     * @param string $idSession
     *
     * @return void
     */
    public function saveSessionCustomer(int $idCustomer, string $idSession): void
    {
        $this->getFactory()
            ->createSessionCustomerFileHandler()
            ->saveSessionAccount($idCustomer, $idSession);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     * @param string $idSession
     *
     * @return bool
     */
    public function isSessionCustomerValid(int $idCustomer, string $idSession): bool
    {
        return $this->getFactory()
            ->createSessionCustomerFileHandler()
            ->isSessionAccountValid($idCustomer, $idSession);
    }
}
