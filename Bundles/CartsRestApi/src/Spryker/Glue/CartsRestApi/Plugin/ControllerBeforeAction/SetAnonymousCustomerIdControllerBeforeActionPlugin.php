<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Plugin\ControllerBeforeAction;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\CartsRestApi\CartsRestApiFactory getFactory()
 */
class SetAnonymousCustomerIdControllerBeforeActionPlugin extends AbstractPlugin implements ControllerBeforeActionPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Sets customer reference value from X-Anonymous-Customer-Unique-Id header.
     *
     * @api
     *
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function beforeAction(string $action, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createRestRequestUpdater()
            ->updateRestRequestWithAnonymousCustomerId($restRequest);
    }
}
