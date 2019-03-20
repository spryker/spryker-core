<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Oauth\Dependency\Service;


class OauthToUtilEncodingServiceBridge implements OauthToUtilEncodingServiceInterface
{
    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct($utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $data
     * @param string $format
     *
     * @return array|null
     *
     * @throws \Spryker\Service\UtilEncoding\Exception\FormatNotSupportedException
     */
    public function decodeFromFormat(string $data, string $format): ?array
    {
        return $this->utilEncodingService->decodeFromFormat($data, $format);
    }
}
