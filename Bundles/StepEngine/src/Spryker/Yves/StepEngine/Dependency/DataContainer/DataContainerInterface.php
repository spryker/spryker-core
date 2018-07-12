<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Dependency\DataContainer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface DataContainerInterface
{
    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function get();

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return void
     */
    public function set(AbstractTransfer $quoteTransfer);
}
