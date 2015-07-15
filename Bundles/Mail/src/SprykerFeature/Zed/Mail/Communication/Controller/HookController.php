<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Mail\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HookController extends AbstractController
{

    public function rejectAction(Request $request)
    {
        //TODO
        //loop over all plugins that in Settings that implement an interface like MailRejectionHandlerPluginInterface
        //methods:
        //handleMailRejection(messageId, recipient, rejectReason)
    }

}
