<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGuiExtension\Plugin;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface ContentPluginInterface
{
    /**
     * @return string
     */
    public function getTermKey(): string;

    /**
     * @return string
     */
    public function getTypeKey(): string;

    /**
     * @return string
     */
    public function getForm(): string;

    /**
     * @param array|null $params
     *
     * @return \Generated\Shared\Transfer\ContentAbstractProductListTransfer
     */
    public function getTransferObject(?array $params = null): AbstractTransfer;
}
