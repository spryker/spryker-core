<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SymfonyMailer\Business;

use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SymfonyMailer\Business\SymfonyMailerBusinessFactory getFactory()
 */
class SymfonyMailerFacade extends AbstractFacade implements SymfonyMailerFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function send(MailTransfer $mailTransfer): void
    {
        $this->getFactory()->createSymfonyMailerAdapter()->send($mailTransfer);
    }
}
