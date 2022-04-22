<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecretsManagerAws\Adapter;

use Aws\Exception\AwsException;
use Aws\SecretsManager\SecretsManagerClient;
use Generated\Shared\Transfer\SecretTransfer;
use Spryker\Client\SecretsManagerAws\Generator\SecretGeneratorInterface;
use Spryker\Shared\Log\LoggerTrait;

class SecretsManagerAwsAdapter implements SecretsManagerAwsAdapterInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const KEY_CONTEXT_EXCEPTION = 'exception';

    /**
     * @var \Aws\SecretsManager\SecretsManagerClient
     */
    protected $secretsManagerAwsClient;

    /**
     * @var \Spryker\Client\SecretsManagerAws\Generator\SecretGeneratorInterface
     */
    protected $secretGenerator;

    /**
     * @param \Aws\SecretsManager\SecretsManagerClient $secretsManagerAwsClient
     * @param \Spryker\Client\SecretsManagerAws\Generator\SecretGeneratorInterface $secretGenerator
     */
    public function __construct(
        SecretsManagerClient $secretsManagerAwsClient,
        SecretGeneratorInterface $secretGenerator
    ) {
        $this->secretsManagerAwsClient = $secretsManagerAwsClient;
        $this->secretGenerator = $secretGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\SecretTransfer $secretTransfer
     *
     * @return bool
     */
    public function createSecret(SecretTransfer $secretTransfer): bool
    {
        $secretTransfer->requireValue()
            ->requireSecretKey()
            ->getSecretKeyOrFail()
                ->requireIdentifier()
                ->requirePrefix();

        $createSecretRequestBody = [
            'Name' => $this->secretGenerator->generateName($secretTransfer->getSecretKeyOrFail()),
            'SecretString' => $secretTransfer->getValueOrFail(),
        ];

        if (count($secretTransfer->getSecretTags()) > 0) {
            foreach ($secretTransfer->getSecretTags() as $secretTagTransfer) {
                $createSecretRequestBody['Tags'][] = [
                    'Key' => $secretTagTransfer->getKey(),
                    'Value' => $secretTagTransfer->getValue(),
                ];
            }
        }

        try {
            $this->secretsManagerAwsClient->createSecret($createSecretRequestBody);
        } catch (AwsException $exception) {
            $this->getLogger()->error($exception->getMessage(), [static::KEY_CONTEXT_EXCEPTION => $exception]);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\SecretTransfer $secretTransfer
     *
     * @return \Generated\Shared\Transfer\SecretTransfer
     */
    public function getSecret(SecretTransfer $secretTransfer): SecretTransfer
    {
        $secretTransfer->requireSecretKey()
            ->getSecretKeyOrFail()
                ->requireIdentifier()
                ->requirePrefix();

        $getSecretValueResult = null;
        $secretTransfer->setValue(null);

        try {
            $getSecretValueResult = $this->secretsManagerAwsClient->getSecretValue([
                'SecretId' => $this->secretGenerator->generateName($secretTransfer->getSecretKeyOrFail()),
            ]);
        } catch (AwsException $exception) {
            $this->getLogger()->error($exception->getMessage(), [static::KEY_CONTEXT_EXCEPTION => $exception]);
        }

        if ($getSecretValueResult !== null && $getSecretValueResult->get('SecretString')) {
            $secretTransfer->setValue($getSecretValueResult->get('SecretString'));
        }

        return $secretTransfer;
    }
}
