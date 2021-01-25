<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryExporter\Model;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class Navigation
{
    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $keyValueReader;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $urlBuilder;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $keyValueReader
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $urlBuilder
     */
    public function __construct(StorageClientInterface $keyValueReader, KeyBuilderInterface $urlBuilder)
    {
        $this->keyValueReader = $keyValueReader;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @param string $locale
     *
     * @return array
     */
    public function getCategories($locale)
    {
        $urlKey = $this->urlBuilder->generateKey([], $locale);
        $categories = $this->keyValueReader->get($urlKey);
        if ($categories) {
            return $categories;
        }

        return [];
    }
}
