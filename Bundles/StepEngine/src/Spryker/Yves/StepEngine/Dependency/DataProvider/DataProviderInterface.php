<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngine\Dependency\DataProvider;

use Spryker\Shared\Transfer\AbstractTransfer;

interface DataProviderInterface
{

    /**
     * @return AbstractTransfer
     */
    public function getData();

    /**
     * @return array
     */
    public function getOptions();

}
