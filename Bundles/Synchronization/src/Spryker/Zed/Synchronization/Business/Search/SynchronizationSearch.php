<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Search;

use Elastica\Exception\NotFoundException;
use Spryker\Zed\Synchronization\Business\Synchronization\SynchronizationInterface;
use Spryker\Zed\Synchronization\Business\Validation\OutdatedValidatorInterface;
use Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchClientInterface;

class SynchronizationSearch implements SynchronizationInterface
{
    const KEY = 'key';
    const VALUE = 'value';
    const TYPE = 'type';
    const INDEX = 'index';
    const TIMESTAMP = '_timestamp';

    /**
     * @var \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchClientInterface
     */
    protected $searchClient;

    /**
     * @var \Spryker\Zed\Synchronization\Business\Validation\OutdatedValidatorInterface
     */
    protected $outdatedValidator;

    /**
     * @param \Spryker\Zed\Synchronization\Dependency\Client\SynchronizationToSearchClientInterface $searchClient
     * @param \Spryker\Zed\Synchronization\Business\Validation\OutdatedValidatorInterface $outdatedValidator
     */
    public function __construct(SynchronizationToSearchClientInterface $searchClient, OutdatedValidatorInterface $outdatedValidator)
    {
        $this->searchClient = $searchClient;
        $this->outdatedValidator = $outdatedValidator;
    }

    /**
     * @param array $data
     * @param string $queueName
     *
     * @return void
     */
    public function write(array $data, $queueName)
    {
        $typeName = $this->getParam($data, static::TYPE);
        $indexName = $this->getParam($data, static::INDEX);
        $data = $this->formatTimestamp($data);
        $existingEntry = $this->read($data[static::KEY]);

        $formattedData = [
            $data[static::KEY] => $data[static::VALUE],
        ];

        if ($existingEntry !== null && $this->outdatedValidator->isInvalid($queueName, $data[static::VALUE], $existingEntry)) {
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
        $typeName = $this->getParam($data, static::TYPE);
        $indexName = $this->getParam($data, static::INDEX);
        $data = $this->formatTimestamp($data);
        $existingEntry = $this->read($data[static::KEY]);

        $formattedData = [
            $data[static::KEY] => [],
        ];

        if ($existingEntry !== null && $this->outdatedValidator->isInvalid($queueName, $data[static::VALUE], $existingEntry)) {
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
     * @return array|null
     */
    protected function read($key)
    {
        try {
            return $this->searchClient->read($key)->getData();
        } catch (NotFoundException $exception) {
            return null;
        }
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function formatTimestamp(array $data)
    {
        if (!isset($data[static::VALUE][static::TIMESTAMP])) {
            return $data;
        }

        $data[static::VALUE]['timestamp'] = $data[static::VALUE][static::TIMESTAMP];
        unset($data[static::VALUE][static::TIMESTAMP]);

        return $data;
    }
}
