<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SessionFile\Builder;

use Generated\Shared\Transfer\SessionEntityRequestTransfer;

interface SessionEntityFileNameBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SessionEntityRequestTransfer $sessionEntityRequestTransfer
     *
     * @return string
     */
    public function build(SessionEntityRequestTransfer $sessionEntityRequestTransfer): string;
}
