<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Dependency\Client;

interface StoresApiToGlossaryStorageClientInterface
{
    /**
     * @param string $id
     * @param string $localeName
     * @param array<string> $parameters
     *
     * @return string
     */
    public function translate(string $id, string $localeName, array $parameters = []): string;
}
