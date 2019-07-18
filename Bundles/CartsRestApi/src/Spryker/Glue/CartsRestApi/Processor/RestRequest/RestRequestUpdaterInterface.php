<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\RestRequest;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface RestRequestUpdaterInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function updateRestRequestWithAnonymousCustomerId(RestRequestInterface $restRequest): void;
}
