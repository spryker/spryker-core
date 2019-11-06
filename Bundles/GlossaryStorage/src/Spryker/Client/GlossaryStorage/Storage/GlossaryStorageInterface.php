<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage\Storage;

interface GlossaryStorageInterface
{
    /**
     * @param string $keyName
     * @param string $localeName
     * @param array $parameters
     *
     * @return string
     */
    public function translate($keyName, $localeName, array $parameters = []);

    /**
     * @param string[] $keyNames
     * @param string $localeName
     * @param array $parameters
     *
     * @return string[]
     */
    public function translateBulk(array $keyNames, string $localeName, array $parameters = []): array;
}
