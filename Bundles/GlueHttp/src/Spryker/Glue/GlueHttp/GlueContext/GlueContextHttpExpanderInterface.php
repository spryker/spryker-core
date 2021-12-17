<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueHttp\GlueContext;

use Generated\Shared\Transfer\GlueApiContextTransfer;

interface GlueContextHttpExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueApiContextTransfer $glueApiContextTransfer
     *
     * @return \Generated\Shared\Transfer\GlueApiContextTransfer
     */
    public function expand(GlueApiContextTransfer $glueApiContextTransfer): GlueApiContextTransfer;
}
