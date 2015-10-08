<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Storage\Communication\Table;

use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class StorageTable extends AbstractTable
{

    const ACTIONS = 'Actions';

    /**
     * @var StorageClientInterface
     */
    protected $storageClient;

    /**
     * @param StorageClientInterface $storageClient
     */
    public function __construct(StorageClientInterface $storageClient)
    {
        $this->storageClient = $storageClient;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
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
     * @param TableConfiguration $config
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
