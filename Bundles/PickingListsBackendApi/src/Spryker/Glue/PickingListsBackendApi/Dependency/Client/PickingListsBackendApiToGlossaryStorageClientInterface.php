<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Dependency\Client;

interface PickingListsBackendApiToGlossaryStorageClientInterface
{
    /**
     * @param list<string> $keyNames
     * @param string $localeName
     * @param array<array<string>> $parameters
     *
     * @return array<string>
     */
    public function translateBulk(array $keyNames, string $localeName, array $parameters = []): array;
}
