<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Mail\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HookController extends AbstractController
{

    /**
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
