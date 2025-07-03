<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageGui\Communication\Table;

use Spryker\Client\Storage\Exception\InvalidStorageScanPluginInterfaceException;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\StorageGui\Dependency\Client\StorageGuiToStorageClientInterface;
use Spryker\Zed\StorageGui\Dependency\Service\StorageGuiToUtilSanitizeServiceInterface;
use Spryker\Zed\StorageGui\StorageGuiConfig;

class StorageTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const MATCH_ALL = '*';

    /**
     * @var int
     */
    protected const DEFAULT_PAGE_LENGTH = 100;

    /**
     * @var int
     */
    protected const VALUE_LENGTH_LIMIT = 120;

    /**
     * @var string
     */
    protected const TABLE_COL_KEY = 'key';

    /**
     * @var string
     */
    protected const TABLE_COL_VALUE = 'value';

    /**
     * @var string
     */
    protected const TABLE_URL = 'list-ajax';

    /**
     * @uses \Spryker\Zed\StorageGui\Communication\Controller\MaintenanceController::keyAction()
     *
     * @var string
     */
    protected const ROUTE_MAINTAIN_KEY = '/storage-gui/maintenance/key';

    /**
     * @uses \Spryker\Zed\StorageGui\Communication\Controller\MaintenanceController::URL_PARAM_KEY
     *
     * @var string
     */
    protected const URL_PARAM_KEY = 'key';

    /**
     * @var int
     */
    protected int $defaultPageLength;

    /**
     * @var \Spryker\Zed\StorageGui\Dependency\Client\StorageGuiToStorageClientInterface
     */
    protected StorageGuiToStorageClientInterface $storageClient;

    /**
     * @var \Spryker\Zed\StorageGui\Dependency\Service\StorageGuiToUtilSanitizeServiceInterface
     */
    protected StorageGuiToUtilSanitizeServiceInterface $utilSanitizeService;

    /**
     * @param \Spryker\Zed\StorageGui\Dependency\Client\StorageGuiToStorageClientInterface $storageClient
     * @param \Spryker\Zed\StorageGui\Dependency\Service\StorageGuiToUtilSanitizeServiceInterface $utilSanitizeService
     * @param int|null $defaultPageLength
     */
    public function __construct(
        StorageGuiToStorageClientInterface $storageClient,
        StorageGuiToUtilSanitizeServiceInterface $utilSanitizeService,
        ?int $defaultPageLength = null
    ) {
        $this->storageClient = $storageClient;
        $this->utilSanitizeService = $utilSanitizeService;
        $this->defaultPageLength = $defaultPageLength ?? static::DEFAULT_PAGE_LENGTH;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config->setHeader([
            static::TABLE_COL_KEY => 'Key',
            static::TABLE_COL_VALUE => 'Value',
        ]);

        $config->setPaging(false);
        $config->setUrl(static::TABLE_URL);

        $config->setRawColumns([static::TABLE_COL_KEY]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<array<string, string>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $data = [];
        foreach ($this->getValues() as $key => $value) {
            $data[] = [
                static::TABLE_COL_KEY => sprintf(
                    '<a href="%s">%s</a>',
                    $this->createKeyUrl($key),
                    $this->utilSanitizeService->escapeHtml($key),
                ),
                static::TABLE_COL_VALUE => sprintf('%s....', mb_substr($value, 0, static::VALUE_LENGTH_LIMIT)),
            ];
        }

        $this->setTotal($this->storageClient->getCountItems());
        $this->setFiltered(count($data));

        return $data;
    }

    /**
     * @return string
     */
    public function getSearchTerm(): string
    {
        if (parent::getSearchTerm() == null || parent::getSearchTerm()[static::TABLE_COL_VALUE] === '') {
            return static::MATCH_ALL;
        }

        return preg_replace(sprintf('/^%s/', StorageGuiConfig::KV_PREFIX), '', parent::getSearchTerm()[static::TABLE_COL_VALUE]);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isRedisPattern(string $key): bool
    {
        return preg_match('/[*?\[\]]/', $key) === 1;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function createKeyUrl(string $key): string
    {
        return Url::generate(static::ROUTE_MAINTAIN_KEY, [static::URL_PARAM_KEY => $key]);
    }

    /**
     * @return list<string>
     */
    protected function getKeys(): array
    {
        $searchTerm = $this->getSearchTerm();
        if (!$searchTerm) {
            return [];
        }
        if (!$this->isRedisPattern($searchTerm)) {
            $value = $this->storageClient->get($this->getSearchTerm());
            if ($value) {
                return [$this->getSearchTerm()];
            }
        }

        try {
            $keys = $this->storageClient->scanKeys(
                $this->getSearchTerm(),
                $this->defaultPageLength,
            )->getKeys();
        } catch (InvalidStorageScanPluginInterfaceException $exception) {
            $keys = array_slice(
                $this->storageClient->getKeys($this->getSearchTerm()),
                0,
                $this->defaultPageLength,
            );
        }

        return array_map(function (string $key) {
            return str_replace(StorageGuiConfig::KV_PREFIX, '', $key);
        }, $keys);
    }

    /**
     * @return array<string, string>
     */
    protected function getValues(): array
    {
        $keys = $this->getKeys();

        $values = [];
        foreach ($this->storageClient->getMulti($keys) as $i => $value) {
            $i = str_replace(StorageGuiConfig::KV_PREFIX, '', $i);
            $values[$i] = $value;
        }

        return $this->storageClient->getMulti($keys);
    }
}
