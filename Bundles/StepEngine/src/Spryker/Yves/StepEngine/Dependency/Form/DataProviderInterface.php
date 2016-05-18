<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Dependency\Form;

use Spryker\Shared\Transfer\AbstractTransfer;

interface DataProviderInterface
{

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer $transfer
     *
     * @return \Spryker\Shared\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $transfer);

    /**
     * @param \Spryker\Shared\Transfer\AbstractTransfer $transfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $transfer);

}
