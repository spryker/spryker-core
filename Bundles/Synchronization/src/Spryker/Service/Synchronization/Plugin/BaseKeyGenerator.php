<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Synchronization\Plugin;

use Generated\Shared\Transfer\SynchronizationDataTransfer;

class BaseKeyGenerator
{
    /**
     * @var string
     */
    protected $resource;

    /**
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param string $resource
     *
     * @return void
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $dataTransfer
     *
     * @return string
     */
    protected function getStoreAndLocaleKey(SynchronizationDataTransfer $dataTransfer)
    {
        $store = $dataTransfer->getStore();
        $locale = $dataTransfer->getLocale();

        $key = '';
        if (!empty($store)) {
            $key = sprintf('%s', strtolower($store));
        }

        if (!empty($locale)) {
            if (!empty($key)) {
                $key .= ':';
            }

            $key = sprintf('%s%s', $key, strtolower($locale));
        }

        return $key;
    }
}
