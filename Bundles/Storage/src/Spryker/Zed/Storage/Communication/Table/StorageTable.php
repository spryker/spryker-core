<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Storage\Communication\Table;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class StorageTable extends AbstractTable
{

    const ACTIONS = 'Actions';

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

        foreach ($values as $key => $value) {
            $key = str_replace('kv:', '', $key);
            $result[] = [
                'key' => '<a href="/storage/maintenance/key?key=' . $key . '">' . $key . '</a>',
                'value' => htmlentities(substr($value, 0, 200)),
            ];
        }

        $this->setTotal(count($result));

        return $result;
    }

}
