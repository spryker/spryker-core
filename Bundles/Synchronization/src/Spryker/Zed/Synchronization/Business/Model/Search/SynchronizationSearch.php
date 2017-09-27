<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Model\Search;

use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\Synchronization\Business\Model\AbstractSynchronization;
use Spryker\Zed\Synchronization\Business\Model\SynchronizationInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchInterface;
use Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingInterface;
use Spryker\Zed\Synchronization\SynchronizationConfig;
use Throwable;

class SynchronizationSearch extends AbstractSynchronization implements SynchronizationInterface
{

    const MESSAGE_TIMESTAMP = 'timestamp';

    /**
     * @var \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchInterface
     */
    protected $searchClient;

    /**
     * SynchronizationSearch constructor.
     *
     * @param \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchInterface $searchClient
     * @param \Spryker\Zed\Synchronization\Dependency\Service\SynchronizationToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\Synchronization\SynchronizationConfig $config
     */
    public function __construct(SynchronizationToSearchInterface $searchClient, SynchronizationToUtilEncodingInterface $utilEncodingService, SynchronizationConfig $config)
    {
        parent::__construct($utilEncodingService, $config);

        $this->searchClient = $searchClient;
    }

    /**
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function write(array $data, $queueName)
    {
        $typeName = $this->getParam($data, 'type');
        $indexName = $this->getParam($data, 'index');
        $data = $this->formatTimestamp($data);

        $formattedData = [
            $data['key'] => $data['value'],
        ];

        if ($this->isInvalid($queueName, $data['key'], $data['value'])) {
            return;
        }

        $this->searchClient->write($formattedData, $typeName, $indexName);
    }

    /**
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function delete(array $data, $queueName)
    {
        $typeName = $this->getParam($data, 'type');
        $indexName = $this->getParam($data, 'index');
        $data = $this->formatTimestamp($data);

        $formattedData = [
            $data['key'] => [],
        ];

        if ($this->isInvalid($queueName, $data['key'], $data['value'])) {
            return;
        }

        $this->searchClient->delete($formattedData, $typeName, $indexName);
    }

    /**
     * @param array $data
     * @param string $parameterName
     *
     * @return string
     */
    protected function getParam(array $data, $parameterName)
    {
        $value = '';
        if (isset($data['params'][$parameterName])) {
            $value = $data['params'][$parameterName];
        }

        return $value;
    }

    /**
     * @param string $key
     *
     * @return array
     */
    protected function getExistEntryByKey($key)
    {
        try {
            return $this->searchClient->read($key)->getData();
        } catch (Throwable $exception) {
            ErrorLogger::getInstance()->log($exception);

            return [];
        }
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function formatTimestamp(array $data)
    {
        if (!isset($data['value']['_timestamp'])) {
            return $data;
        }

        $data['value']['timestamp'] = $data['value']['_timestamp'];
        unset($data['value']['_timestamp']);

        return $data;
    }

}
