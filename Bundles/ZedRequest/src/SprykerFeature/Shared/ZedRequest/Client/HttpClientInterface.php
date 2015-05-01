<?php

namespace SprykerFeature\Shared\ZedRequest\Client;

use SprykerFeature\Shared\Library\TransferObject\TransferInterface;

/**
 * Interface HttpClientInterface
 * @package SprykerFeature\Shared\Library\ZedRequest\Client
 */
interface HttpClientInterface
{
    /**
     * @param int $timeoutInSeconds
     */
    public static function setDefaultTimeout($timeoutInSeconds);

    /**
     * @param string $pathInfo
     * @param TransferInterface $transferObject
     * @param array $metaTransfers
     * @param null $timeoutInSeconds
     * @param bool $isBackgroundRequest
     * @return \SprykerFeature\Shared\Library\Communication\Response
     * @throws \LogicException
     */
    public function request(
        $pathInfo,
        TransferInterface $transferObject = null,
        array $metaTransfers = [],
        $timeoutInSeconds = null,
        $isBackgroundRequest = false
    );

    /**
     * Used for debug output
     * @return int
     */
    public static function getRequestCounter();
}