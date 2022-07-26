<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthApi\Processor\Extractor;

use Generated\Shared\Transfer\GlueRequestTransfer;

interface AccessTokenExtractorInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<string>|null
     */
    public function extract(GlueRequestTransfer $glueRequestTransfer): ?array;

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    public function isAuthorizationHeaderSet(GlueRequestTransfer $glueRequestTransfer): bool;
}
