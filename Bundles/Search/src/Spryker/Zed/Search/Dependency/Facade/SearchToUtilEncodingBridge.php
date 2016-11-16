<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Dependency\Facade;

use Spryker\Zed\UtilEncoding\Business\UtilEncodingFacadeInterface;

class SearchToUtilEncodingBridge implements SearchToUtilEncodingInterface
{

    /**
     * @var \Spryker\Zed\UtilEncoding\Business\UtilEncodingFacadeInterface
     */
    protected $utilEncodeFacade;

    /**
     * @param \Spryker\Zed\UtilEncoding\Business\UtilEncodingFacadeInterface $utilEncodeFacade
     */
    public function __construct(UtilEncodingFacadeInterface $utilEncodeFacade)
    {
        $this->utilEncodeFacade = $utilEncodeFacade;
    }

    /**
     * @param string $jsonValue
     * @param bool $assoc
     * @param int|null $depth
     * @param int|null $options
     *
     * @return array
     */
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null)
    {
        return $this->utilEncodeFacade->decodeJson($jsonValue, $assoc, $depth, $options);
    }

}
