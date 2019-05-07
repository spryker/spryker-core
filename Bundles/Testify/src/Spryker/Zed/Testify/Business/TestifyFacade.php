<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @api
 *
 * @method \Spryker\Zed\Testify\Business\TestifyBusinessFactory getFactory()
 */
class TestifyFacade extends AbstractFacade implements TestifyFacadeInterface
{
    /**
     * @api
     *
     * @return array
     */
    public function cleanUpOutputDirectories(): array
    {
        return $this->getFactory()->createOutputCleaner()->cleanup();
    }
}
