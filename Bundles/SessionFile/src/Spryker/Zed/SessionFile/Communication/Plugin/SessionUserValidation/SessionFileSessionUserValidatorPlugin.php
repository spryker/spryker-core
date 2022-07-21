<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionFile\Communication\Plugin\SessionUserValidation;

use Generated\Shared\Transfer\SessionUserTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface;

/**
 * @method \Spryker\Zed\SessionFile\Business\SessionFileFacadeInterface getFacade()
 * @method \Spryker\Zed\SessionFile\Communication\SessionFileCommunicationFactory getFactory()
 * @method \Spryker\Zed\SessionFile\SessionFileConfig getConfig()
 */
class SessionFileSessionUserValidatorPlugin extends AbstractPlugin implements SessionUserValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Retrieves ID session by `idUser` from file and compare with current.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SessionUserTransfer $sessionUserTransfer
     *
     * @return bool
     */
    public function isSessionUserValid(SessionUserTransfer $sessionUserTransfer): bool
    {
        return $this->getFacade()->isSessionUserValid(
            $sessionUserTransfer->getIdUserOrFail(),
            $sessionUserTransfer->getIdSessionOrFail(),
        );
    }
}
