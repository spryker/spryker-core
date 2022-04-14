<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecretsManagerAws\Generator;

use Generated\Shared\Transfer\SecretKeyTransfer;
use Spryker\Client\SecretsManagerAws\Dependency\Service\SecretsManagerAwsToUtilTextServiceInterface;

class SecretGenerator implements SecretGeneratorInterface
{
    /**
     * @var \Spryker\Client\SecretsManagerAws\Dependency\Service\SecretsManagerAwsToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Client\SecretsManagerAws\Dependency\Service\SecretsManagerAwsToUtilTextServiceInterface $utilTextService
     */
    public function __construct(SecretsManagerAwsToUtilTextServiceInterface $utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\SecretKeyTransfer $secretKeyTransfer
     *
     * @return string
     */
    public function generateName(SecretKeyTransfer $secretKeyTransfer): string
    {
        return sprintf(
            '%s-%s',
            $secretKeyTransfer->getPrefixOrFail(),
            $this->utilTextService->hashValue($secretKeyTransfer->getIdentifierOrFail(), 'sha1'),
        );
    }
}
