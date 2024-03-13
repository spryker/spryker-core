<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Dependency\External;

use Codeception\Configuration;
use Codeception\Lib\Di;
use Codeception\Lib\ModuleContainer;

class TestifyBackendApiToCodeceptionAdapter implements TestifyBackendApiToCodeceptionAdapterInterface
{
    /**
     * @param string|null $configFile
     *
     * @return array<string, mixed>
     */
    public function config(?string $configFile = null): array
    {
        return Configuration::config($configFile);
    }

    /**
     * @param string $suite
     * @param array<string, mixed> $config
     *
     * @return array<string, string>
     */
    public function suiteSettings(string $suite, array $config): array
    {
        return Configuration::suiteSettings($suite, $config);
    }

    /**
     * @param array<string, mixed> $settings
     *
     * @return list<string>
     */
    public function modules(array $settings): array
    {
        return Configuration::modules($settings);
    }

    /**
     * @param array<string, string> $config
     *
     * @return \Codeception\Lib\ModuleContainer
     */
    public function creteModuleContainer(array $config): ModuleContainer
    {
        return new ModuleContainer(new Di(), $config);
    }
}
