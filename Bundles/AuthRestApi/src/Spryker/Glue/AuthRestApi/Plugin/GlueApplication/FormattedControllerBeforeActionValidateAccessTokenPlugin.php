<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormattedControllerBeforeActionTerminatePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\TerminationAwareBeforeActionPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Glue\AuthRestApi\AuthRestApiFactory getFactory()
 */
class FormattedControllerBeforeActionValidateAccessTokenPlugin extends AbstractPlugin implements FormattedControllerBeforeActionTerminatePluginInterface, TerminationAwareBeforeActionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates Oauth access token of HTTP request 'authorization' header.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function beforeAction(Request $request): ?RestErrorMessageTransfer
    {
        return $this->getFactory()
            ->createAccessTokenValidator()
            ->validate($request);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function terminateOnFailure(): bool
    {
        return true;
    }
}
