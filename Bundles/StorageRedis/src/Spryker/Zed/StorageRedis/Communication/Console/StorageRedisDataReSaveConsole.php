<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StorageRedis\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\StoreAwareConsole;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\StorageRedis\Communication\StorageRedisCommunicationFactory getFactory()
 */
class StorageRedisDataReSaveConsole extends StoreAwareConsole
{
    /**
     * @var string
     */
    protected const COMMAND_NAME = 'storage:redis:re-save';

    /**
     * @uses \Spryker\Client\StorageRedis\Redis\StorageRedisWrapper::KV_PREFIX
     *
     * @var string
     */
    protected const KV_PREFIX = 'kv:';

    /**
     * @var int
     */
    protected const BULK_SIZE = 100;

    /**
     * @var int
     */
    protected const DEFAULT_SLEEP_TIME = 10;

    /**
     * @var float
     */
    protected const DEFAULT_MAX_DURATION = 0.05;

    /**
     * @var string
     */
    protected const OPTION_CURSOR = 'cursor';

    /**
     * @var string
     */
    protected const OPTION_CURSOR_SHORT = 'c';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('Re-saves storage data with current settings. Uses bulk operation with adaptive timeout between iterations. Can be used in production mode.')
            ->addOption(static::OPTION_CURSOR, static::OPTION_CURSOR_SHORT, InputOption::VALUE_OPTIONAL, 'Defines a cursor for scanning keys in sotrage.', 0);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('max_execution_time', 0);
        $cursor = (int)$input->getOption(static::OPTION_CURSOR);

        gc_enable();
        $sleepMs = static::DEFAULT_SLEEP_TIME;
        $maxDuration = static::DEFAULT_MAX_DURATION;
        $redis = $this->getFactory()->getStorageRedisClient();
        $countItems = 0;
        do {
            $start = microtime(true);

            $storageScanResultTransfer = $redis->scanKeys('*', static::BULK_SIZE, $cursor);
            $cursor = $storageScanResultTransfer->getCursor();
            $countItems += count($storageScanResultTransfer->getKeys());
            foreach ($storageScanResultTransfer->getKeys() as $key) {
                $key = ltrim($key, 'kv:');
                $value = $redis->get($key);
                if (!$value) {
                    continue;
                }
                if (is_array($value)) {
                    $value = json_encode($value);
                }
                $redis->set($key, $value);
            }
            unset($storageScanResultTransfer);

            $duration = microtime(true) - $start;

            if ($duration > $maxDuration) {
                $sleepMs = min($sleepMs + static::DEFAULT_SLEEP_TIME, 200);
            } elseif ($sleepMs > static::DEFAULT_SLEEP_TIME) {
                $sleepMs = max($sleepMs - 5, static::DEFAULT_SLEEP_TIME);
            }
            echo sprintf("\rProgress: %s items. Current cursor: %s", $countItems, $cursor);

            gc_collect_cycles();
            usleep($sleepMs * 1000);
        } while ($cursor !== 0);

        echo "\n";

        return static::CODE_SUCCESS;
    }
}
