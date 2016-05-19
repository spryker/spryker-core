<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Dependency\DataContainer;

use Spryker\Shared\Transfer\AbstractTransfer;

interface DataContainerInterface
{

    /**
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    public function get();

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer $dataTransfer
     *
     * @return void
     */
    public function set(AbstractTransfer $dataTransfer);

}
