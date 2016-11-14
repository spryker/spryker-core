<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library\Generator;

use Spryker\Service\UtilText\Model\StringGenerator AS UtilStringGenerator;

/**
 * @deprecated use \Spryker\Service\UtilText\UtilTextService instead
 */
class StringGenerator
{

    /**
     * @var \Spryker\Service\UtilText\Model\StringGenerator
     */
    protected static $utilStringGenerator = null;

    /**
     * @var int
     */
    private $length = 32;

    /**
     * @param int $length
     *
     * @return $this
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return string
     */
    public function generateRandomString()
    {
        return static::createUtilStringGenerator()->generateRandomString($this->length);
    }

    /**
     * @var \Spryker\Service\UtilText\Model\StringGenerator
     *
     * @return \Spryker\Service\UtilText\Model\StringGenerator
     */
    protected static function createUtilStringGenerator()
    {
        if (static::$utilStringGenerator === null) {
            static::$utilStringGenerator = new UtilStringGenerator();
        }

        return static::$utilStringGenerator;
    }

}
