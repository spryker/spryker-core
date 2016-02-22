<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Business;

use Generated\Shared\Transfer\SendMailResponsesTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Mail\Business\MailBusinessFactory getFactory()
 */
class MailFacade extends AbstractFacade implements MailFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\SendMailResponsesTransfer
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        return $this->getFactory()->createMailSender()->sendMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SendMailResponsesTransfer $mailResponses
     *
     * @return bool
     */
    public function isMailSent(SendMailResponsesTransfer $mailResponses)
    {
        return $this->getFactory()->createMailSender()->isMailSent($mailResponses);
    }

}
