<?php

namespace SprykerFeature\Yves\FrontendExporter\Business\Matcher;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;

/**
 * Class UrlMatcher
 * @package SprykerFeature\Yves\FrontendExporter\Business\Matcher
 */
class UrlMatcher implements UrlMatcherInterface
{
    /**
     * @var
     */
    private $urlKeyBuilder;

    /**
     * @var ReadInterface
     */
    private $keyValueReader;

    /**
     * @param KeyBuilderInterface $urlKeyBuilder
     * @param ReadInterface       $keyValueReader
     */
    public function __construct(KeyBuilderInterface $urlKeyBuilder, ReadInterface $keyValueReader)
    {
        $this->urlKeyBuilder = $urlKeyBuilder;
        $this->keyValueReader = $keyValueReader;
    }

    /**
     * @param string $url
     * @param string $locale
     * @return mixed
     */
    public function matchUrl($url, $locale)
    {
        $urlKey = $this->urlKeyBuilder->generateKey($url, $locale);

        return $this->keyValueReader->get($urlKey);
    }
}
