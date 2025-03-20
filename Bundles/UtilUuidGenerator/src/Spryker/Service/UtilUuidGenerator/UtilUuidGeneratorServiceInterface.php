<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUuidGenerator;

use Generated\Shared\Transfer\IdGeneratorSettingsTransfer;

interface UtilUuidGeneratorServiceInterface
{
    /**
     * Specification:
     * - generates UUID version 5 basing on given resource name and OID namespace.
     *
     * @api
     *
     * @param string $name
     *
     * @return string
     */
    public function generateUuid5FromObjectId(string $name): string;

    /**
     * Specification:
     * - Generates a unique random ID.
     * - Generated value is unique and can be used as a unique identifier.
     * - Generated values are not sortable.
     * - Use `IdGeneratorSettingsTransfer.alphabet` to specify the characters to use for the generated ID. More symbols - fewer chances for collision.
     * - Use `IdGeneratorSettingsTransfer.size` to specify the length of the generated ID. Longer size - fewer chances for collision.
     * - Use `IdGeneratorSettingsTransfer.splitLength` with `IdGeneratorSettingsTransfer.splitSeparator` to split the generated ID into chunks.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\IdGeneratorSettingsTransfer $idGeneratorSettingsTransfer
     *
     * @return string
     */
    public function generateUniqueRandomId(IdGeneratorSettingsTransfer $idGeneratorSettingsTransfer): string;
}
