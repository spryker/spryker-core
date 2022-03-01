<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Will be removed without replacement.
 *
 * @method \Spryker\Glue\GlueApplication\GlueApplicationConfig getConfig()
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class CorsValidateHttpRequestPlugin extends AbstractPlugin implements ValidateHttpRequestPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Validates that the HTTP method is OPTIONS.
     *  - Validates that the access-control-request-method header is present.
     *  - Validates that the access-control-request-headers header is present.
     *  - Validates that the origin header is present.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer|null
     */
    public function validate(Request $request): ?RestErrorMessageTransfer
    {
        return $this->getFactory()
            ->createCorsHttpRequestValidator()
            ->validate($request);
    }
}
