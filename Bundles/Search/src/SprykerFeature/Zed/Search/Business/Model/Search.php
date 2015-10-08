<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Search\Business\Model;

use Elastica\Document;
use Elastica\Exception\ResponseException;
use Elastica\Response;
use SprykerFeature\Client\Search\Service\SearchClient;

class Search
{

    /**
     * @var SearchClient
     */
    private $searchClient;

    /**
     * @param SearchClient $searchClient
     */
    public function __construct(SearchClient $searchClient)
    {
        $this->searchClient = $searchClient;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        try {
            return $this->searchClient->getIndexClient()->count();
        } catch (ResponseException $e) {
            return 0;
        }
    }

    /**
     * @return array
     */
    public function getMetaData()
    {
        $metaData = [];

        try {
            $mapping = $this->searchClient->getIndexClient()->getMapping();

            if (isset($mapping['page']) && isset($mapping['page']['_meta'])) {
                $metaData = $mapping['page']['_meta'];
            }
        } catch (ResponseException $e) {
        }

        return $metaData;
    }

    /**
     * @return Response
     */
    public function delete()
    {
        return $this->searchClient->getIndexClient()->delete();
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return Document
     */
    public function getDocument($key, $type)
    {
        return $this->searchClient->getIndexClient()->getType($type)->getDocument($key);
    }

}
