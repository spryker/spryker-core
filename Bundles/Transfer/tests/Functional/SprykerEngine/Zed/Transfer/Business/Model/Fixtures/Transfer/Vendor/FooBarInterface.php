<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Vendor;

use SprykerEngine\Shared\Transfer\TransferInterface;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
interface FooBarInterface extends TransferInterface
{

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param int $bla
     *
     * @return $this
     */
    public function setBla($bla);

    /**
     * @return int
     */
    public function getBla();


}
