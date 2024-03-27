<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantPortalGui\Communication\Translator;

use ArrayObject;

interface MessageTranslatorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MessageTransfer> $messageTransfers
     *
     * @return list<string>
     */
    public function translateErrorMessages(ArrayObject $messageTransfers): array;
}
