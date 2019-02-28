<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGuiExtension\Dependency\Plugin;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface ContentPluginInterface
{
    /**
     * Specification:
     * - Returns term key.
     *
     * @api
     *
     * @return string
     */
    public function getTermKey(): string;

    /**
     * Specification:
     * - Returns type key.
     *
     * @api
     *
     * @return string
     */
    public function getTypeKey(): string;

    /**
     * Specification:
     * - Returns form name.
     *
     * @api
     *
     * @return string
     */
    public function getForm(): string;

    /**
     * Specification:
     * - Data mappings to a object.
     *
     * @api
     *
     * @param array|null $params
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getTransferObject(?array $params = null): TransferInterface;
}
