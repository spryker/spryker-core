<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Mapping;

use Elastica\Client;
use Elastica\Index;
use Elastica\Mapping;
use Elastica\Response;
use Generated\Shared\Transfer\SearchCollectorConfigurationTransfer;

/**
 * @method $this setMeta(array $meta)
 */
class TypelessMappingAdapter implements MappingAdapterInterface
{
    /**
     * @var \Elastica\Mapping
     */
    protected $elasticaMapping;

    /**
     * @var \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    protected $searchCollectorConfigurationTransfer;

    /**
     * @var \Elastica\Client
     */
    protected $elasticaClient;

    /**
     * @param \Elastica\Client $elasticaClient
     * @param \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer $searchCollectorConfigurationTransfer
     * @param array $mappingProperties
     */
    public function __construct(
        Client $elasticaClient,
        SearchCollectorConfigurationTransfer $searchCollectorConfigurationTransfer,
        array $mappingProperties = []
    ) {
        $this->elasticaClient = $elasticaClient;
        $this->searchCollectorConfigurationTransfer = $searchCollectorConfigurationTransfer;
        $this->elasticaMapping = new Mapping($mappingProperties);
    }

    /**
     * @return \Elastica\Response
     */
    public function send(): Response
    {
        return $this->elasticaMapping->send($this->getIndex());
    }

    /**
     * @param string $methodName
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($methodName, $arguments)
    {
        $result = call_user_func_array([$this->elasticaMapping, $methodName], $arguments);

        if ($result === $this->elasticaMapping) {
            return $this;
        }

        return $result;
    }

    /**
     * @return \Elastica\Index
     */
    protected function getIndex(): Index
    {
        return $this->elasticaClient->getIndex(
            $this->searchCollectorConfigurationTransfer->getIndexName()
        );
    }
}
