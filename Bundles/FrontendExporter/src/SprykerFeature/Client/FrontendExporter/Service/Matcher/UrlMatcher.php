<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\FrontendExporter\Service\Matcher;

use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class UrlMatcher implements UrlMatcherInterface
{

    /**
     * @var
     */
    protected $urlKeyBuilder;

    /**
     * @var StorageClientInterface
     */
    protected $keyValueReader;

    /**
     * @param KeyBuilderInterface $urlKeyBuilder
     * @param StorageClientInterface $keyValueReader
     */
    public function __construct(KeyBuilderInterface $urlKeyBuilder, StorageClientInterface $keyValueReader)
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
