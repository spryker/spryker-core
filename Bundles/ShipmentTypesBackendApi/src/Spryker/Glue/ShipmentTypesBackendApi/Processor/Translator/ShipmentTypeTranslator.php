<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Processor\Translator;

use ArrayObject;
use Spryker\Glue\ShipmentTypesBackendApi\Dependency\Client\ShipmentTypesBackendApiToGlossaryStorageClientInterface;
use Spryker\Glue\ShipmentTypesBackendApi\ShipmentTypesBackendApiConfig;

class ShipmentTypeTranslator implements ShipmentTypeTranslatorInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypesBackendApi\ShipmentTypesBackendApiConfig
     */
    protected ShipmentTypesBackendApiConfig $shipmentTypesBackendApiConfig;

    /**
     * @var \Spryker\Glue\ShipmentTypesBackendApi\Dependency\Client\ShipmentTypesBackendApiToGlossaryStorageClientInterface
     */
    protected ShipmentTypesBackendApiToGlossaryStorageClientInterface $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ShipmentTypesBackendApi\ShipmentTypesBackendApiConfig $shipmentTypesBackendApiConfig
     * @param \Spryker\Glue\ShipmentTypesBackendApi\Dependency\Client\ShipmentTypesBackendApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        ShipmentTypesBackendApiConfig $shipmentTypesBackendApiConfig,
        ShipmentTypesBackendApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->shipmentTypesBackendApiConfig = $shipmentTypesBackendApiConfig;
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string|null $localeName
     *
     * @return array<string, string>
     */
    public function translateErrorTransferMessages(ArrayObject $errorTransfers, ?string $localeName = null): array
    {
        $glossaryKeys = $this->extractUniqueGlossaryKeysFromErrorTransfers($errorTransfers);
        $parametersIndexedByGlossaryKeys = $this->getParametersIndexedByMessages($errorTransfers);

        return $this->glossaryStorageClient->translateBulk(
            $glossaryKeys,
            $localeName ?? $this->shipmentTypesBackendApiConfig->getDefaultLocaleName(),
            $parametersIndexedByGlossaryKeys,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return array<int, string>
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
     * @return array<string, array<string, mixed>>
     */
    protected function getParametersIndexedByMessages(ArrayObject $errorTransfers): array
    {
        $parametersIndexedByGlossaryKeys = [];
        foreach ($errorTransfers as $errorTransfer) {
            $parametersIndexedByGlossaryKeys[$errorTransfer->getMessageOrFail()] = $errorTransfer->getParameters();
        }

        return $parametersIndexedByGlossaryKeys;
    }
}
