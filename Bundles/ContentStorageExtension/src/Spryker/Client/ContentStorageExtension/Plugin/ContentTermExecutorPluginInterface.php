<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ContentStorageExtension\Plugin;

use Generated\Shared\Transfer\ContentAbstractProductListTransfer;

interface ContentTermExecutorPluginInterface
{
    /**
     * @api
     *
     * @param array $parameters
     *
     * @return array
     */
    public function execute(array $parameters): array;

    /**
     * @api
     *
     * @return string
     */
    public function getTermKey(): string;

    /**
     * @api
     *
     * @return string
     */
    public function getTypeKey(): string;

    /**
     * @api
     *
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\ContentAbstractProductListTransfer
     */
    public function mapParametersToTransferObject(array $parameters): ContentAbstractProductListTransfer;
}
