<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApi\Processor\Builder;

use Generated\Shared\Transfer\GlueRequestTransfer;

interface RequestBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildUserRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer;
}
