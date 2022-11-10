<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Lib\ModuleContainer;

trait ModuleHelperConfigTrait
{
    /**
     * @param \Codeception\Lib\ModuleContainer $moduleContainer
     * @param array|null $config
     */
    public function __construct(ModuleContainer $moduleContainer, ?array $config = null)
    {
        $this->setDefaultConfig();

        parent::__construct($moduleContainer, $config);
    }

    /**
     * @return void
     */
    abstract protected function setDefaultConfig(): void;
}
