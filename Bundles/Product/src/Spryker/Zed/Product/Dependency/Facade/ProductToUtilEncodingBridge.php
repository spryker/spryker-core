<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Dependency\Facade;

class ProductToUtilEncodingBridge implements ProductToUtilEncodingInterface
{

    /**
     * @var \Spryker\Zed\UtilEncoding\Business\UtilEncodingFacadeInterface
     */
    protected $utilEncodingFacade;

    /**
     * @param \Spryker\Zed\UtilEncoding\Business\UtilEncodingFacadeInterface $utilEncodingFacade
     */
    public function __construct($utilEncodingFacade)
    {
        $this->utilEncodingFacade = $utilEncodingFacade;
    }

    /**
     * @param array $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string
     */
    public function encodeJson($value, $options = null, $depth = null)
    {
        return $this->utilEncodingFacade->encodeJson($value, $options, $depth);
    }

    /**
     * @param string $jsonString
     * @param bool $assoc
     * @param null $depth
     * @param null $options
     *
     * @return array
     */
    public function decodeJson($jsonString, $assoc = false, $depth = null, $options = null)
    {
        return $this->utilEncodingFacade->decodeJson($jsonString, $assoc, $depth, $options);
    }

}
