<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use SprykerEngine\Shared\Transfer\AbstractTransfer;
use Generated\Shared\Project\FooBarInterface as ProjectFooBarInterface;
use Generated\Shared\Vendor\FooBarInterface as VendorFooBarInterface;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class FooBarTransfer extends AbstractTransfer implements ProjectFooBarInterface, VendorFooBarInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $bla;

    /**
     * @var \ArrayObject|FooBarTransfer[]
     */
    protected $selfReference;

    public function __construct()
    {
        $this->selfReference = new \ArrayObject();
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty('name');

        return $this;
    }

    /**
     * @param bool $isRequired
     *
     * @return string
     */
    public function getName($isRequired = false)
    {
        $this->assertIsRequired('name', $isRequired);

        return $this->name;
    }

    /**
     * @param int $bla
     *
     * @return $this
     */
    public function setBla($bla)
    {
        $this->bla = $bla;
        $this->addModifiedProperty('bla');

        return $this;
    }

    /**
     * @param bool $isRequired
     *
     * @return int
     */
    public function getBla($isRequired = false)
    {
        $this->assertIsRequired('bla', $isRequired);

        return $this->bla;
    }

    /**
     * @param \ArrayObject|FooBarTransfer[] $selfReference
     *
     * @return $this
     */
    public function setSelfReference(\ArrayObject $selfReference)
    {
        $this->selfReference = $selfReference;
        $this->addModifiedProperty('selfReference');

        return $this;
    }

    /**
     * @param bool $isRequired
     *
     * @return FooBarTransfer[]
     */
    public function getSelfReference($isRequired = false)
    {
        $this->assertIsRequired('selfReference', $isRequired);

        return $this->selfReference;
    }

    /**
     * @param FooBarTransfer $selfReference
     *
     * @return $this
     */
    public function addSelfReference(FooBarTransfer $selfReference)
    {
        $this->selfReference[] = $selfReference;
        $this->addModifiedProperty('selfReference');

        return $this;
    }

}
