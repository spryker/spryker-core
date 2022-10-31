<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Expander;

use Symfony\Component\HttpFoundation\Request;

interface RequestExpanderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function expandRequestWithCustomerReference(Request $request): void;
}
