<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Translator;

use ArrayObject;
use Spryker\Glue\ServicePointsBackendApi\Dependency\Client\ServicePointsBackendApiToGlossaryStorageClientInterface;

class ServicePointTranslator implements ServicePointTranslatorInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Dependency\Client\ServicePointsBackendApiToGlossaryStorageClientInterface
     */
    protected ServicePointsBackendApiToGlossaryStorageClientInterface $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\Dependency\Client\ServicePointsBackendApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        ServicePointsBackendApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string $localeName
     *
     * @return array<string, string>
     */
    public function translateErrorTransferMessages(ArrayObject $errorTransfers, string $localeName): array
    {
        $glossaryKeys = $this->extractUniqueGlossaryKeysFromErrorTransfers($errorTransfers);
        $parametersIndexedByGlossaryKeys = $this->getParametersIndexedByGlossaryKeys($errorTransfers);

        return $this->glossaryStorageClient->translateBulk(
            $glossaryKeys,
            $localeName,
            $parametersIndexedByGlossaryKeys,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return list<string>
     */
    protected function extractUniqueGlossaryKeysFromErrorTransfers(ArrayObject $errorTransfers): array
    {
        $glossaryKeys = [];

        foreach ($errorTransfers as $errorTransfer) {
            $glossaryKeys[] = $errorTransfer->getMessageOrFail();
        }

        return array_unique($glossaryKeys);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return array<string, list<mixed>>
     */
    protected function getParametersIndexedByGlossaryKeys(ArrayObject $errorTransfers): array
    {
        $parametersIndexedByGlossaryKeys = [];

        foreach ($errorTransfers as $errorTransfer) {
            $parametersIndexedByGlossaryKeys[$errorTransfer->getMessageOrFail()] = $errorTransfer->getParameters();
        }

        return $parametersIndexedByGlossaryKeys;
    }
}
