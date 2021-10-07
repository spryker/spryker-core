<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client;

interface ConfigurableBundlesRestApiToGlossaryStorageClientInterface
{
    /**
     * @param array<string> $keyNames
     * @param string $localeName
     * @param array<string[]> $parameters
     *
     * @return array<string>
     */
    public function translateBulk(array $keyNames, string $localeName, array $parameters = []): array;
}
