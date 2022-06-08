<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionFile\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SessionFile\Business\SessionFileBusinessFactory getFactory()
 */
class SessionFileFacade extends AbstractFacade implements SessionFileFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idUser
     * @param string $idSession
     *
     * @return void
     */
    public function saveSessionUser(int $idUser, string $idSession): void
    {
        $this->getFactory()
            ->createSessionUserFileHandler()
            ->saveSessionAccount($idUser, $idSession);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idUser
     * @param string $idSession
     *
     * @return bool
     */
    public function isSessionUserValid(int $idUser, string $idSession): bool
    {
        return $this->getFactory()
            ->createSessionUserFileHandler()
            ->isSessionAccountValid($idUser, $idSession);
    }
}
