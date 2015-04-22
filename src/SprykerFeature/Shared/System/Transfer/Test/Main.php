<?php 

namespace SprykerFeature\Shared\System\Transfer\Test;

/**
 *
 */
class Main extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $foo = null;

    protected $bar = null;

    protected $camelCased = null;

    protected $childTransfer = 'System\\Test\\Child';

    protected $interfaceChild = null;

    protected $interfaceChild_ClassName = null;

    /**
     * @param string $foo
     * @return $this
     */
    public function setFoo($foo)
    {
        $this->foo = $foo;
        $this->addModifiedProperty('foo');
        return $this;
    }

    /**
     * @return string
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param string $bar
     * @return $this
     */
    public function setBar($bar)
    {
        $this->bar = $bar;
        $this->addModifiedProperty('bar');
        return $this;
    }

    /**
     * @return string
     */
    public function getBar()
    {
        return $this->bar;
    }

    /**
     * @param string $camelCased
     * @return $this
     */
    public function setCamelCased($camelCased)
    {
        $this->camelCased = $camelCased;
        $this->addModifiedProperty('camelCased');
        return $this;
    }

    /**
     * @return string
     */
    public function getCamelCased()
    {
        return $this->camelCased;
    }

    /**
     * @param \SprykerFeature\Shared\System\Transfer\Test\Child $childTransfer
     * @return $this
     */
    public function setChildTransfer(\SprykerFeature\Shared\System\Transfer\Test\Child $childTransfer)
    {
        $this->childTransfer = $childTransfer;
        $this->addModifiedProperty('childTransfer');
        return $this;
    }

    /**
     * @return \SprykerFeature\Shared\System\Transfer\Test\Child
     */
    public function getChildTransfer()
    {
        return $this->childTransfer;
    }

    /**
     * @param \PhpUnit\SprykerFeature\Shared\System\TestInterface $interfaceChild
     * @return $this
     */
    public function setInterfaceChild(\PhpUnit\SprykerFeature\Shared\System\TestInterface $interfaceChild)
    {
        $this->interfaceChild = $interfaceChild;
        $this->interfaceChild_ClassName = get_class($interfaceChild);
        $this->addModifiedProperty('interfaceChild');
        return $this;
    }

    /**
     * @return \PhpUnit\SprykerFeature\Shared\System\TestInterface
     */
    public function getInterfaceChild()
    {
        if (is_array($this->interfaceChild) && !empty($this->interfaceChild_ClassName)) {
            if (is_a($this->interfaceChild_ClassName, '\SprykerFeature\Shared\Library\TransferObject\TransferInterface', true)) {
                $loaderName = \SprykerFeature\Shared\Library\CodeGenerator\TransferLoaderGenerator::generateGetMethodName($this->interfaceChild_ClassName);
                $this->interfaceChild = \SprykerFeature\Shared\Library\TransferLoader::$loaderName($this->interfaceChild);
            }
        }
        return $this->interfaceChild;
    }


}
