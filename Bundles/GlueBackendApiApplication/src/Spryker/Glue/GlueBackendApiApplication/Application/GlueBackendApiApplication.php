<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Application;

use Spryker\Glue\GlueApplication\ApiApplication\Type\RequestFlowAgnosticApiApplication;
use Symfony\Component\HttpFoundation\JsonResponse;

class GlueBackendApiApplication extends RequestFlowAgnosticApiApplication
{
    /**
     * {@inheritDoc}
     * - The Simplest possible ApiApplication implementation for demonstration purpose only
     *
     * @api
     *
     * @return void
     */
    public function run(): void
    {
        $response = new JsonResponse('Welcome to the future Backend API', 200);
        $response->send();
    }
}
