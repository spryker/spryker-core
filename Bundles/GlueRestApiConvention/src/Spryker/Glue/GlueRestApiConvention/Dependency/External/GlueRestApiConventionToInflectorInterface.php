<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueRestApiConvention\Dependency\External;

interface GlueRestApiConventionToInflectorInterface
{
    /**
     * @param string $word
     *
     * @return string
     */
    public function singularize(string $word): string;
}
