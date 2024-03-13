<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi\Dependency\External;

use Codeception\Lib\ModuleContainer;

interface TestifyBackendApiToCodeceptionAdapterInterface
{
    /**
     * @param string|null $configFile
     *
     * @return array<string, mixed>
     */
    public function config(?string $configFile = null): array;

    /**
     * @param string $suite
     * @param array<string, mixed> $config
     *
     * @return array<string, string>
     */
    public function suiteSettings(string $suite, array $config): array;

    /**
     * @param array<string, mixed> $settings
     *
     * @return list<string>
     */
    public function modules(array $settings): array;

    /**
     * @param array<string, string> $config
     *
     * @return \Codeception\Lib\ModuleContainer
     */
    public function creteModuleContainer(array $config): ModuleContainer;
}
