<?php


namespace SprykerFeature\Shared\UrlExporter\Code\KeyBuilder;


use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderTrait;

class ResourceKeyBuilder implements KeyBuilderInterface
{
    use KeyBuilderTrait;

    /**
     * @param array $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return $data['resourceType'] . '.' . $data['value'];
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'resource';
    }
}
