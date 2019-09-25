<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Synchronization\Plugin;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Service\Synchronization\Model\KeyFilterInterface;

class DefaultKeyGeneratorPlugin extends BaseKeyGenerator implements SynchronizationKeyGeneratorPluginInterface
{
    /**
     * @var \Spryker\Service\Synchronization\Model\KeyFilterInterface
     */
    protected $keyFilter;

    /**
     * @param \Spryker\Service\Synchronization\Model\KeyFilterInterface $keyFilter
     */
    public function __construct(KeyFilterInterface $keyFilter)
    {
        $this->keyFilter = $keyFilter;
    }

    /**
     * @inheritDoc
     *
     * @param \Generated\Shared\Transfer\SynchronizationDataTransfer $dataTransfer
     *
     * @return string
     */
    public function generateKey(SynchronizationDataTransfer $dataTransfer)
    {
        $reference = $this->keyFilter->escapeKey($dataTransfer->getReference());
        $localeAndStore = $this->getStoreAndLocaleKey($dataTransfer);
        if (!empty($reference) && !empty($localeAndStore)) {
            $keySuffix = sprintf('%s:%s', $this->getStoreAndLocaleKey($dataTransfer), $reference);
        } else {
            $keySuffix = sprintf('%s%s', $this->getStoreAndLocaleKey($dataTransfer), $reference);
        }

        if (empty($keySuffix)) {
            return $this->getResource();
        }

        return sprintf("%s:%s", $this->getResource(), $keySuffix);
    }
}
