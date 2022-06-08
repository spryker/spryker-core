<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SecretsManagerAws;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SecretsManagerAwsConstants
{
    /**
     * Specification:
     * - Defines AWS access key used for Secrets Manager.
     *
     * @api
     *
     * @var string
     */
    public const SECRETS_MANAGER_AWS_ACCESS_KEY = 'SECRETS_MANAGER_AWS:SECRETS_MANAGER_AWS_ACCESS_KEY';

    /**
     * Specification:
     * - Defines AWS secret used for Secrets Manager.
     *
     * @api
     *
     * @var string
     */
    public const SECRETS_MANAGER_AWS_ACCESS_SECRET = 'SECRETS_MANAGER_AWS:SECRETS_MANAGER_AWS_ACCESS_SECRET';

    /**
     * Specification:
     * - Defines AWS region used for Secrets Manager.
     *
     * @api
     *
     * @var string
     */
    public const SECRETS_MANAGER_AWS_REGION = 'SECRETS_MANAGER_AWS:SECRETS_MANAGER_AWS_REGION';

    /**
     * Specification:
     * - Defines AWS Secrets Manager API endpoint.
     *
     * @api
     *
     * @var string
     */
    public const SECRETS_MANAGER_AWS_ENDPOINT = 'SECRETS_MANAGER_AWS:SECRETS_MANAGER_AWS_ENDPOINT';
}
