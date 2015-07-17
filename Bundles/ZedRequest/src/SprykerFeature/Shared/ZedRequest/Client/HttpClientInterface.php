<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\ZedRequest\Client;

use SprykerEngine\Shared\Transfer\TransferInterface;

/**
 * Interface HttpClientInterface
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
     *
     * @throws \LogicException
     *
     * @return \SprykerFeature\Shared\Library\Communication\Response
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
     *
     * @return int
     */
    public static function getRequestCounter();

}
