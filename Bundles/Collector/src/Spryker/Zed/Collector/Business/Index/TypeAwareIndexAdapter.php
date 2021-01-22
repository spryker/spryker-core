<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Index;

use Elastica\Client;
use Elastica\Document;
use Elastica\Type;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;

class TypeAwareIndexAdapter implements IndexAdapterInterface
{
    /**
     * @var \Elastica\Index
     */
    protected $elasticaIndex;

    /**
     * @var \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    protected $searchCollectorConfigurationTransfer;

    /**
     * @param \Elastica\Client $elasticaClient
     * @param \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer $searchCollectorConfigurationTransfer
     */
    public function __construct(Client $elasticaClient, SearchCollectorConfigurationTransfer $searchCollectorConfigurationTransfer)
    {
        $this->elasticaIndex = $elasticaClient->getIndex($searchCollectorConfigurationTransfer->getIndexName());
        $this->searchCollectorConfigurationTransfer = $searchCollectorConfigurationTransfer;
    }

    /**
     * @param int|string $id
     * @param array $options
     *
     * @return \Elastica\Document
     */
    public function getDocument($id, array $options = []): Document
    {
        return $this->getType()->getDocument($id, $options);
    }

    /**
     * @param \Elastica\Document[] $documents
     * @param array $options
     *
     * @return \Elastica\Bulk\ResponseSet
     */
    public function addDocuments(array $documents, array $options = [])
    {
        return $this->getType()->addDocuments($documents, $options);
    }

    /**
     * @return array
     */
    public function getMapping(): array
    {
        $mappingType = $this->getType();
        $typeName = $mappingType->getName();
        $mapping = $mappingType->getMapping();

        return $mapping[$typeName] ?? [];
    }

    /**
     * @param string $methodName
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($methodName, $arguments)
    {
        $result = call_user_func_array([$this->elasticaIndex, $methodName], $arguments);

        if ($result === $this->elasticaIndex) {
            return $this;
        }

        return $result;
    }

    /**
     * @return \Elastica\Type
     */
    protected function getType(): Type
    {
        return $this->elasticaIndex->getType(
            $this->searchCollectorConfigurationTransfer->getTypeName()
        );
    }
}
