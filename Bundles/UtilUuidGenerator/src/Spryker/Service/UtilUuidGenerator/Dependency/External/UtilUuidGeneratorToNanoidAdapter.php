<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUuidGenerator\Dependency\External;

use Generated\Shared\Transfer\IdGeneratorSettingsTransfer;
use Hidehalo\Nanoid\Client;

class UtilUuidGeneratorToNanoidAdapter implements UtilUuidGeneratorToNanoidInterface
{
    /**
     * @param \Generated\Shared\Transfer\IdGeneratorSettingsTransfer $idGeneratorSettingsTransfer
     *
     * @return string
     */
    public function generateUniqueRandomId(IdGeneratorSettingsTransfer $idGeneratorSettingsTransfer): string
    {
        $uniqueRandomId = (new Client())->formattedId(
            $idGeneratorSettingsTransfer->getAlphabet() ?? '',
            $idGeneratorSettingsTransfer->getSize() ?? 0,
        );

        if ($idGeneratorSettingsTransfer->getSplitLength() && $idGeneratorSettingsTransfer->getSplitSeparator()) {
            $length = max(1, $idGeneratorSettingsTransfer->getSplitLength());
            $uniqueRandomId = implode(
                $idGeneratorSettingsTransfer->getSplitSeparator(),
                str_split($uniqueRandomId, $length),
            );
        }

        return $uniqueRandomId;
    }
}
