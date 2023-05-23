<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi\Dependency\Client;

interface ShipmentTypesBackendApiToGlossaryStorageClientInterface
{
    /**
     * @param list<string> $keyNames
     * @param string $localeName
     * @param array<string, array<string, mixed>> $parameters
     *
     * @return array<string, string>
     */
    public function translateBulk(array $keyNames, string $localeName, array $parameters = []): array;
}
