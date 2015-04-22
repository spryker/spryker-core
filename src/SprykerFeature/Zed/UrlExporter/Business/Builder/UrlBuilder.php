<?php

namespace SprykerFeature\Zed\UrlExporter\Business\Builder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class UrlBuilder implements UrlBuilderInterface
{
    /**
     * @var KeyBuilderInterface
     */
    protected $urlKeyBuilder;
    /**
     * @var KeyBuilderInterface
     */
    private $resourceKeyBuilder;

    /**
     * @param KeyBuilderInterface $urlKeyBuilder
     * @param KeyBuilderInterface $resourceKeyBuilder
     */
    public function __construct(KeyBuilderInterface $urlKeyBuilder, KeyBuilderInterface $resourceKeyBuilder)
    {
        $this->urlKeyBuilder = $urlKeyBuilder;
        $this->resourceKeyBuilder = $resourceKeyBuilder;
    }

    /**
     * @param array $urlResultSet
     * @param string $locale
     *
     * @return array
     */
    public function buildUrls(array $urlResultSet, $locale)
    {
        $returnedResultSet = [];
        foreach ($urlResultSet as $index => $url) {
            $resourceArguments = $this->findResourceArguments($url);

            if (!$resourceArguments) {
                continue;
                //log a warning about a faulty url here
            }

            $indexKey = $this->urlKeyBuilder->generateKey($url['url'], $locale);
            $referenceKey = $this->resourceKeyBuilder->generateKey($resourceArguments, $locale);
            $returnedResultSet[$indexKey] = [
                'reference_key' => $referenceKey,
                'type' => $resourceArguments['resourceType']
            ];
        }

        return $returnedResultSet;
    }

    /**
     * @param array $url
     *
     * @return array
     */
    protected function findResourceArguments(array $url)
    {
        foreach ($url as $columnName => $value) {
            if (strpos($columnName, 'fk_resource_') !== 0) {
                continue;
            }
            if ($value !== null) {
                $resourceType = str_replace('fk_resource_', '', $columnName);
                $resourceType = str_replace('_id', '', $resourceType);

                return [
                    'resourceType' => $resourceType,
                    'value' => $value
                ];
            }
        }
        return false;
    }
}
