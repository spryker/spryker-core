<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\TestifyAsyncApi\Business\Codeception\Helper;

use Codeception\Module;

trait AsyncApiHelperTrait
{
    /**
     * @return \Spryker\Zed\TestifyAsyncApi\Business\Codeception\Helper\AsyncApiHelper
     */
    protected function getAsyncApiHelper(): AsyncApiHelper
    {
        /** @var \Spryker\Zed\TestifyAsyncApi\Business\Codeception\Helper\AsyncApiHelper $asyncApiHelper */
        $asyncApiHelper = $this->getModule('\\' . AsyncApiHelper::class);

        return $asyncApiHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
