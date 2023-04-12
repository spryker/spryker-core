<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Translator;

use ArrayObject;

interface ServicePointTranslatorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string $localeName
     *
     * @return array<string, string>
     */
    public function translateErrorTransferMessages(ArrayObject $errorTransfers, string $localeName): array;
}
