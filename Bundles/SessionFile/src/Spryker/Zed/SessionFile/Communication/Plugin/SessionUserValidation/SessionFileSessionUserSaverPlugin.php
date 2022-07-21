<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionFile\Communication\Plugin\SessionUserValidation;

use Generated\Shared\Transfer\SessionUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserSaverPluginInterface;

/**
 * @method \Spryker\Zed\SessionFile\Business\SessionFileFacadeInterface getFacade()
 * @method \Spryker\Zed\SessionFile\Communication\SessionFileCommunicationFactory getFactory()
 * @method \Spryker\Zed\SessionFile\SessionFileConfig getConfig()
 */
class SessionFileSessionUserSaverPlugin extends AbstractPlugin implements SessionUserSaverPluginInterface
{
    /**
     * {@inheritDoc}
     * - Saves `SessionUser` to file.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionUserTransfer $sessionUserTransfer
     *
     * @return void
     */
    public function saveSessionUser(SessionUserTransfer $sessionUserTransfer): void
    {
        $this->getFacade()->saveSessionUser(
            $sessionUserTransfer->getIdUserOrFail(),
            $sessionUserTransfer->getIdSessionOrFail(),
        );
    }
}
