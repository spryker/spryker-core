<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface;

interface RestUserFinderPluginInterface
{
    /**
     * Specification:
     * - Finds rest user based on rest request data.
     * - Fills user information to the rest user.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return null|\Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface
     */
    public function findUser(RestRequestInterface $restRequest): ?UserInterface;
}
