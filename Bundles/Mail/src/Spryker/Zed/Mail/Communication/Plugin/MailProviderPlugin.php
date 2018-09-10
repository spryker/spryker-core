<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Communication\Plugin;

use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Mail\Dependency\Plugin\MailProviderPluginInterface;

/**
 * @method \Spryker\Zed\Mail\Business\MailFacadeInterface getFacade()
 * @method \Spryker\Zed\Mail\Communication\MailCommunicationFactory getFactory()
 */
class MailProviderPlugin extends AbstractPlugin implements MailProviderPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return void
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        $this->getFacade()->sendMail($mailTransfer);
    }
}
