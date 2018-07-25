<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUuidGenerator;

interface UtilUuidGeneratorServiceInterface
{
    /**
     * Specification:
     * - generates UUID version 5 basing on given resource name and OID namespace
     *
     * @api
     *
     * @param string $name
     *
     * @return string
     */
    public function generateUuid5WithOidNamespace(string $name): string;
}
