<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Processor\Locator;

use Codeception\Lib\ModuleContainer;
use Spryker\Glue\TestifyBackendApi\Dependency\External\TestifyBackendApiToCodeceptionAdapterInterface;
use Spryker\Glue\TestifyBackendApi\Processor\TestStubs\TestStub;
use Spryker\Glue\TestifyBackendApi\TestifyBackendApiConfig;

class CodeceptionModuleContainer implements CodeceptionModuleContainerInterface
{
    /**
     * @var \Spryker\Glue\TestifyBackendApi\TestifyBackendApiConfig
     */
    protected TestifyBackendApiConfig $testifyBackendApiConfig;

    /**
     * @var \Spryker\Glue\TestifyBackendApi\Dependency\External\TestifyBackendApiToCodeceptionAdapterInterface
     */
    protected TestifyBackendApiToCodeceptionAdapterInterface $codeceptionAdapter;

    /**
     * @param \Spryker\Glue\TestifyBackendApi\TestifyBackendApiConfig $testifyBackendApiConfig
     * @param \Spryker\Glue\TestifyBackendApi\Dependency\External\TestifyBackendApiToCodeceptionAdapterInterface $codeceptionAdapter
     */
    public function __construct(
        TestifyBackendApiConfig $testifyBackendApiConfig,
        TestifyBackendApiToCodeceptionAdapterInterface $codeceptionAdapter
    ) {
        $this->testifyBackendApiConfig = $testifyBackendApiConfig;
        $this->codeceptionAdapter = $codeceptionAdapter;
    }

    /**
     * @return \Codeception\Lib\ModuleContainer
     */
    public function initModuleContainer(): ModuleContainer
    {
        $codeceptionConfig = $this->codeceptionAdapter->config(
            $this->testifyBackendApiConfig->getCodeceptionConfiguration(),
        );
        $suiteSettings = $this->codeceptionAdapter->suiteSettings(
            $this->testifyBackendApiConfig->getCodeceptionSuiteName(),
            $codeceptionConfig,
        );

        $modules = $this->codeceptionAdapter->modules($suiteSettings);
        $moduleContainer = $this->codeceptionAdapter->creteModuleContainer($suiteSettings);

        foreach ($modules as $moduleName) {
            /** @var \Codeception\Module $module */
            $module = $moduleContainer->create($moduleName);
            $module->_initialize();
            $module->_before(new TestStub());
        }

        return $moduleContainer;
    }
}
