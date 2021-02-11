<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerAfterActionPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\SecurityBlockerRestApi\SecurityBlockerRestApiFactory getFactory()
 */
class SecurityBlockerCustomerControllerAfterActionPlugin extends AbstractPlugin implements ControllerAfterActionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the action is a failed login action and logs the attempt.
     *
     * @api
     *
     * @param string $action
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return void
     */
    public function afterAction(string $action, RestRequestInterface $restRequest, RestResponseInterface $restResponse): void
    {
        $this->getFactory()
            ->createSecurityBlockerStorage()
            ->incrementLoginAttemptCount($action, $restRequest, $restResponse);
    }
}
