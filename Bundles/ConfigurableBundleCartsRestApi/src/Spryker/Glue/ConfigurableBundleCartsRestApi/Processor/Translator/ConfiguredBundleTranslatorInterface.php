<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Translator;

use Generated\Shared\Transfer\ItemTransfer;

interface ConfiguredBundleTranslatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function translateItemTransfer(ItemTransfer $itemTransfer, string $localeName): ItemTransfer;
}
