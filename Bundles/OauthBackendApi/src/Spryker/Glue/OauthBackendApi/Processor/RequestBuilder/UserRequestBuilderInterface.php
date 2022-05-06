<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApi\Processor\RequestBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;

interface UserRequestBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer;
}
