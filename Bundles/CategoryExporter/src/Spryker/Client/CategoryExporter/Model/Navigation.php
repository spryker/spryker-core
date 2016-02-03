<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\CategoryExporter\Model;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class Navigation
{

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    private $keyValueReader;

    /**
     * @var \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    private $urlBuilder;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $keyValueReader
     * @param \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface $urlBuilder
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
