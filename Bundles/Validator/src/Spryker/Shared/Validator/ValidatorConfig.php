<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Validator;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ValidatorConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    protected const ENV_DOCKER_DEV = 'docker.dev';

    /**
     * @var string
     */
    protected const ENV_DOCKER_CI = 'docker.ci';

    /**
     * @var string
     */
    protected const ENV_DOCKER_CI_CYPRESS = 'docker.ci.cypress';

    /**
     * Specification:
     * - Returns a list of environments where validation should be less strict. E.g. compromised password for CI envs can be used for the sake of testing.
     *
     * @api
     *
     * @return array<string>
     */
    public static function getLessStrictEnvironments(): array
    {
        return [
            static::ENV_DOCKER_DEV,
            static::ENV_DOCKER_CI,
            static::ENV_DOCKER_CI_CYPRESS,
        ];
    }
}
