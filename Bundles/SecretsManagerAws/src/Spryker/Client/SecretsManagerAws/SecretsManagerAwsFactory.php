<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecretsManagerAws;

use Aws\SecretsManager\SecretsManagerClient;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SecretsManagerAws\Adapter\SecretsManagerAwsAdapter;
use Spryker\Client\SecretsManagerAws\Adapter\SecretsManagerAwsAdapterInterface;
use Spryker\Client\SecretsManagerAws\Dependency\Service\SecretsManagerAwsToUtilTextServiceInterface;
use Spryker\Client\SecretsManagerAws\Generator\SecretGenerator;
use Spryker\Client\SecretsManagerAws\Generator\SecretGeneratorInterface;

class SecretsManagerAwsFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SecretsManagerAws\Adapter\SecretsManagerAwsAdapterInterface
     */
    public function createSecretsManagerAwsAdapter(): SecretsManagerAwsAdapterInterface
    {
        return new SecretsManagerAwsAdapter(
            $this->getSecretsManagerAwsClient(),
            $this->createSecretGenerator(),
        );
    }

    /**
     * @return \Spryker\Client\SecretsManagerAws\Generator\SecretGeneratorInterface
     */
    public function createSecretGenerator(): SecretGeneratorInterface
    {
        return new SecretGenerator($this->getUtilTextService());
    }

    /**
     * @return \Aws\SecretsManager\SecretsManagerClient
     */
    public function getSecretsManagerAwsClient(): SecretsManagerClient
    {
        return $this->getProvidedDependency(SecretsManagerAwsDependencyProvider::CLIENT_SECRETS_MANAGER_AWS);
    }

    /**
     * @return \Spryker\Client\SecretsManagerAws\Dependency\Service\SecretsManagerAwsToUtilTextServiceInterface
     */
    public function getUtilTextService(): SecretsManagerAwsToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(SecretsManagerAwsDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
