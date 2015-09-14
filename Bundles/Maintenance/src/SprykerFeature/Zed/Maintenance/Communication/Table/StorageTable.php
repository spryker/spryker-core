<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication\Table;

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

    public function __construct(StorageClientInterface $storageClient)
    {
        $this->storageClient = $storageClient;
    }


    /**
     * @inheritDoc
     */
    protected function configure(TableConfiguration $config)
    {
        $headers = [
            'key' => 'Key',
            'value' => 'Value',
        ];

        $config->setHeader($headers);
        $config->setUrl('table');

        return $config;
    }


    /**
     * @inheritDoc
     */
    protected function prepareData(TableConfiguration $config)
    {

        $keys = $this->storageClient->getAllKeys();

        sort($keys);

        $result = [];
        foreach ($keys as $key) {
            $key = str_replace('kv:', '', $key);
            $value = $this->storageClient->get($key);

            $value = print_r($value, true);

            if (is_string($value) && strlen($value) > 100) {
                $value = htmlentities($value);
                $value = substr($value, 0, 255);
            }

            $result[] = [
                'key' => '<a href="/maintenance/data/storage-key?key=' . $key . '">' . $key . '</a>',
                'value' => $value
            ];
        }

        $this->setTotal(count($result));

        return $result;
    }


}
