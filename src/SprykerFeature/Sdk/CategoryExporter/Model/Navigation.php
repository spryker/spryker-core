<?php

namespace SprykerFeature\Sdk\CategoryExporter\Model;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\KvStorage\Client\ReadInterface;

/**
 * Class Navigation
 * @package SprykerFeature\Sdk\CategoryExporter\Model
 */
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
     * @param $keyValueReader
     * @param $urlBuilder
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