<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Loggly\Communication\Plugin;

use Exception;
use Monolog\Handler\Curl\Util;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface;

/**
 * @method \Spryker\Zed\Loggly\LogglyConfig getConfig()
 */
class LogglyLoggerQueueMessageProcessorPlugin extends AbstractPlugin implements QueueMessageProcessorPluginInterface
{

    const HOST = 'logs-01.loggly.com';

    const ENDPOINT_BATCH = 'bulk';

    /**
     * @param \Generated\Shared\Transfer\QueueReceiveMessageTransfer[] $queueMessageTransfers
     *
     * @return \Generated\Shared\Transfer\QueueReceiveMessageTransfer[]
     */
    public function processMessages(array $queueMessageTransfers)
    {
        try {
            $data = '';
            foreach ($queueMessageTransfers as $queueMessageTransfer) {
                $data .= $queueMessageTransfer->getQueueMessage()->getBody() . PHP_EOL;
            }
            $url = sprintf("https://%s/%s/%s/", self::HOST, static::ENDPOINT_BATCH, $this->getConfig()->getLogglyToken());

            $headers = ['Content-Type: application/json'];

            $curlHandler = curl_init();

            curl_setopt($curlHandler, CURLOPT_URL, $url);
            curl_setopt($curlHandler, CURLOPT_POST, true);
            curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curlHandler, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);

            Util::execute($curlHandler);

            foreach ($queueMessageTransfers as $queueMessageTransfer) {
                $queueMessageTransfer->setAcknowledge(true);
            }
        } catch (Exception $e) {
            foreach ($queueMessageTransfers as $queueMessageTransfer) {
                $queueMessageTransfer->setHasError(true);
            }
        }

        return $queueMessageTransfers;
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return $this->getConfig()->getQueueChunkSize();
    }

}
