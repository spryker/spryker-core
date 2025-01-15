<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Synchronization\Plugin;

use Generated\Shared\Transfer\SynchronizationDataTransfer;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Service\Synchronization\Model\KeyFilterInterface;
use Spryker\Service\Synchronization\SynchronizationConfig;

class DefaultKeyGeneratorPlugin extends BaseKeyGenerator implements SynchronizationKeyGeneratorPluginInterface
{
    /**
     * @param \Spryker\Service\Synchronization\Model\KeyFilterInterface $keyFilter
     * @param \Spryker\Service\Synchronization\SynchronizationConfig $synchronizationConfig
     */
    public function __construct(protected KeyFilterInterface $keyFilter, protected SynchronizationConfig $synchronizationConfig)
    {
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
        $reference = $dataTransfer->getReference() ? $this->keyFilter->escapeKey($dataTransfer->getReference()) : null;
        $localeAndStore = $this->getStoreAndLocaleKey($dataTransfer);
        /** @var string $keySuffix */
        $keySuffix = $reference && $localeAndStore
            ? sprintf('%s:%s', $localeAndStore, $reference)
            : sprintf('%s%s', $localeAndStore, $reference);

        if (!$this->synchronizationConfig->isSingleKeyFormatNormalized()) {
            return sprintf('%s:%s', $this->getResource(), $keySuffix);
        }

        return sprintf(
            '%s%s%s',
            $this->getResource(),
            $keySuffix ? ':' : '',
            $keySuffix,
        );
    }
}
