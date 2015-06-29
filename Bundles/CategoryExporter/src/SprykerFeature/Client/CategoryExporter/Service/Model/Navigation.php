<?php

namespace SprykerFeature\Client\CategoryExporter\Service\Model;

use SprykerFeature\Client\KvStorage\Service\KvStorageClientInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class Navigation
{

    /**
     * @var KvStorageClientInterface
     */
    private $keyValueReader;

    /**
     * @var KeyBuilderInterface
     */
    private $urlBuilder;

    /**
     * @param KvStorageClientInterface $keyValueReader
     * @param KeyBuilderInterface $urlBuilder
     */
    public function __construct(KvStorageClientInterface $keyValueReader, KeyBuilderInterface $urlBuilder)
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
