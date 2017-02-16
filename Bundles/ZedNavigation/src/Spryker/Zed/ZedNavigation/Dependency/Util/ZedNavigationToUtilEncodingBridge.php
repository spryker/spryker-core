<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Dependency\Util;

class ZedNavigationToUtilEncodingBridge implements ZedNavigationToUtilEncodingInterface
{

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $utilEncoding
     */
    public function __construct($utilEncoding)
    {
        $this->utilEncodingService = $utilEncoding;
    }

    /**
     * @param string $jsonValue
     * @param null $options
     * @param null $depth
     *
     * @return string
     */
    public function encodeJson($jsonValue, $options = null, $depth = null)
    {
        return $this->utilEncodingService->encodeJson($jsonValue, $options, $depth);
    }

    /**
     * @param string $jsonValue
     * @param bool $assoc
     * @param null $depth
     * @param null $options
     *
     * @return array
     */
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null)
    {
        return $this->utilEncodingService->decodeJson($jsonValue, $assoc, $depth, $options);
    }

}
