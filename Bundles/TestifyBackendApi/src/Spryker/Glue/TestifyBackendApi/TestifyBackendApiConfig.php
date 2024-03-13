<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TestifyBackendApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Glue\TestifyBackendApi\Processor\Exception\CodeceptionConfigurationNotFoundException;

class TestifyBackendApiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Resource name of dynamic-fixtures path.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_DYNAMIC_FIXTURES = 'dynamic-fixtures';

    /**
     * @api
     *
     * @var string
     */
    public const OPERATION_TYPE_HELPER = 'helper';

    /**
     * @api
     *
     * @var string
     */
    public const OPERATION_TYPE_TRANSFER = 'transfer';

    /**
     * @api
     *
     * @var string
     */
    public const OPERATION_TYPE_ARRAY_OBJECT = 'array-object';

    /**
     * @api
     *
     * @var string
     */
    public const OPERATION_TYPE_CLI_COMMAND = 'cli-command';

    /**
     * @api
     *
     * @var string
     */
    public const OPERATION_TYPE_BUILDER = 'builder';

    /**
     * Specification:
     * - Returns the path to the codeception configuration file for the module.
     *
     * @api
     *
     * @throws \Spryker\Glue\TestifyBackendApi\Processor\Exception\CodeceptionConfigurationNotFoundException
     *
     * @return string
     */
    public function getCodeceptionConfiguration(): string
    {
        throw new CodeceptionConfigurationNotFoundException('Codeception configuration file not found');
    }

    /**
     * Specification:
     * - Returns the name of the codeception suite for the module.
     *
     * @api
     *
     * @throws \Spryker\Glue\TestifyBackendApi\Processor\Exception\CodeceptionConfigurationNotFoundException
     *
     * @return string
     */
    public function getCodeceptionSuiteName(): string
    {
        throw new CodeceptionConfigurationNotFoundException('Codeception suite name not found');
    }

    /**
     * @return string
     */
    protected function getModuleRoot(): string
    {
        $moduleRoot = dirname(__DIR__, 4);

        return $moduleRoot . DIRECTORY_SEPARATOR;
    }
}
