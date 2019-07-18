<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Communication\Table;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\UtilSanitize\UtilSanitizeService;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class StorageTable extends AbstractTable
{
    public const ACTIONS = 'Actions';

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storageClient;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storageClient
     */
    public function __construct(StorageClientInterface $storageClient)
    {
        $this->storageClient = $storageClient;
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
        $keys = $this->storageClient->getAllKeys();

        sort($keys);

        $result = [];

        foreach ($keys as $i => $key) {
            $keys[$i] = str_replace('kv:', '', $key);
        }

        $values = $this->storageClient->getMulti($keys);

        $fixedValues = [];
        foreach ($values as $i => $value) {
            $i = str_replace('kv:', '', $i);
            $fixedValues[$i] = $value;
        }
        $values = $fixedValues;

        foreach ($values as $key => $value) {
            $url = Url::generate('/storage/maintenance/key', ['key' => $key]);
            $utilSanitizeService = new UtilSanitizeService();
            $result[] = [
                'key' => '<a href="' . $url . '">' . $utilSanitizeService->escapeHtml($key) . '</a>',
                'value' => substr($value, 0, 200),
            ];
        }

        $this->setTotal(count($result));

        return $result;
    }
}
