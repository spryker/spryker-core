<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormattedControllerBeforeActionPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Glue\CartsRestApi\CartsRestApiFactory getFactory()
 */
class ExpandRequestWithCustomerReferenceFormattedControllerBeforeActionPlugin extends AbstractPlugin implements FormattedControllerBeforeActionPluginInterface
{
    /**
     * {@inheritDoc}
     * - If customer reference is already set in the request, does nothing.
     * - Otherwise, checks if `X-Anonymous-Customer-Unique-Id` request header is set.
     * - If so, expands the request with customer reference based on `X-Anonymous-Customer-Unique-Id` request header.
     * - Otherwise, does nothing.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function beforeAction(Request $request): ?RestErrorMessageTransfer
    {
        $this->getFactory()
            ->createRequestExpander()
            ->expandRequestWithCustomerReference($request);

        return null;
    }
}
