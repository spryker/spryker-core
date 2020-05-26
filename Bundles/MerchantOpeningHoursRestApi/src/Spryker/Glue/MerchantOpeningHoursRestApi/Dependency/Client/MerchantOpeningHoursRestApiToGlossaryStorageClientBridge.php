<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client;

class MerchantOpeningHoursRestApiToGlossaryStorageClientBridge implements MerchantOpeningHoursRestApiToGlossaryStorageClientInterface
{
    /**
     * @var \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct($glossaryStorageClient)
    {
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param string[] $keyNames
     * @param string $localeName
     * @param string[][] $parameters
     *
     * @return string[]
     */
    public function translateBulk(array $keyNames, string $localeName, array $parameters = []): array
    {
        return $this->glossaryStorageClient->translateBulk($keyNames, $localeName, $parameters);
    }
}
