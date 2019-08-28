<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Communication\Table;

use Spryker\Client\Storage\Exception\InvalidStorageScanPluginInterfaceException;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Storage\Dependency\Service\StorageToUtilSanitizeServiceInterface;

class StorageTable extends AbstractTable
{
    protected const KV_PREFIX = 'kv:';
    protected const MATCH_ALL = '*';
    protected const DEFAULT_PAGE_LENGTH = 100;
    protected const VALUE_LENGTH_LIMIT = 120;

    /**
     * @var int
     */
    protected $defaultPageLength;

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Zed\Storage\Dependency\Service\StorageToUtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     * @param \Spryker\Zed\Storage\Dependency\Service\StorageToUtilSanitizeServiceInterface $utilSanitizeService
     * @param int|null $defaultPageLength
     */
    public function __construct(StorageClientInterface $storageClient, StorageToUtilSanitizeServiceInterface $utilSanitizeService, ?int $defaultPageLength = null)
    {
        $this->storageClient = $storageClient;
        $this->utilSanitizeService = $utilSanitizeService;
        $this->defaultPageLength = $defaultPageLength ?? static::DEFAULT_PAGE_LENGTH;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $headers = [
            'key' => 'Key',
            'value' => 'Value',
        ];

        $config->setHeader($headers);
        $config->setPaging(false);
        $config->setUrl('list-ajax');

        $config->setRawColumns(['key']);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        try {
            $keys = $this->storageClient->scanKeys($this->getSearchTerm(), $this->defaultPageLength)->getKeys();
        } catch (InvalidStorageScanPluginInterfaceException $exception) {
            $keys = array_slice($this->storageClient->getKeys($this->getSearchTerm()), 0, $this->defaultPageLength);
        }

        $keys = array_map(function (string $key) {
            return str_replace(static::KV_PREFIX, '', $key);
        }, $keys);

        $values = $this->storageClient->getMulti($keys);

        $fixedValues = [];
        foreach ($values as $i => $value) {
            $i = str_replace(static::KV_PREFIX, '', $i);
            $fixedValues[$i] = $value;
        }
        $values = $fixedValues;

        $result = [];
        foreach ($values as $key => $value) {
            $result[] = [
                'key' => '<a href="' . $this->createKeyUrl($key) . '">' . $this->utilSanitizeService->escapeHtml($key) . '</a>',
                'value' => mb_substr($value, 0, static::VALUE_LENGTH_LIMIT) . '....',
            ];
        }

        $this->setTotal($this->storageClient->getCountItems());
        $this->setFiltered(count($result));

        return $result;
    }

    /**
     * @return string
     */
    public function getSearchTerm(): string
    {
        if (parent::getSearchTerm() == null || parent::getSearchTerm()['value'] === '') {
            return static::MATCH_ALL;
        }

        return parent::getSearchTerm()['value'];
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function createKeyUrl(string $key): string
    {
        return Url::generate('/storage/maintenance/key', ['key' => $key]);
    }
}
