<?php

namespace SprykerFeature\Sdk\CategoryExporter\Model;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\KvStorage\Client\ReadInterface;

class Navigation
{

    /**
     * @var ReadInterface
     */
    private $keyValueReader;

    /**
     * @var KeyBuilderInterface
     */
    private $urlBuilder;

    /**
     * @param ReadInterface $keyValueReader
     * @param KeyBuilderInterface $urlBuilder
     */
    public function __construct(ReadInterface $keyValueReader, KeyBuilderInterface $urlBuilder)
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