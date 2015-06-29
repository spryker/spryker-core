<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Sdk\FrontendExporter\Matcher;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\KvStorage\Client\ReadInterface;

/**
 * Class UrlMatcher
 * @package SprykerFeature\Yves\YvesExport\Matcher
 */
class UrlMatcher implements UrlMatcherInterface
{
    /**
     * @var
     */
    protected $urlKeyBuilder;

    /**
     * @var ReadInterface
     */
    protected $keyValueReader;

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
     * @param string $localeName
     *
     * @return array|bool
     */
    public function matchUrl($url, $localeName)
    {
        $urlKey = $this->urlKeyBuilder->generateKey($url, $localeName);
        $urlDetails = $this->keyValueReader->get($urlKey);
        if ($urlDetails) {
            $data = $this->keyValueReader->get($urlDetails['reference_key']);
            if ($data) {
                return [
                    'type' => $urlDetails['type'],
                    'data' => $data,
                ];
            }
        }

        return false;
    }
}
