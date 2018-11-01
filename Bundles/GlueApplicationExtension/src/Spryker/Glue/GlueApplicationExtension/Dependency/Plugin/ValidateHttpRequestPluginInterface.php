<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplicationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Symfony\Component\HttpFoundation\Request;

interface ValidateHttpRequestPluginInterface
{
    /**
     * @api
     *
     * Specification:
     *  - Validates HTTP request before further processing, terminates on first failure. If null returned proceeds to other validator
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $request): ?RestErrorMessageTransfer;
}
