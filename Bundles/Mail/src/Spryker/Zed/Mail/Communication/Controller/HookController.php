<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Mail\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HookController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function rejectAction(Request $request)
    {
        //TODO
        //loop over all plugins that in Settings that implement an interface like MailRejectionHandlerPluginInterface
        //methods:
        //handleMailRejection(messageId, recipient, rejectReason)
    }

}
