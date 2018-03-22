<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlossaryStorage;

interface GlossaryStorageClientInterface
{
    /**
     * Specification:
     * - Read glossary from storage and returned translated string by the given key and locale.
     * - If there were no translation found, then the key will be returned.
     * - Parameters passed will be replaced in the translated text.
     *
     * @api
     *
     * @param string $id
     * @param string $localeName
     * @param array $parameters
     *
     * @return string
     */
    public function translate($id, $localeName, array $parameters = []);
}
