<?php 

namespace SprykerFeature\Shared\System\Transfer\Test;

/**
 *
 */
class Child extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $baz = null;

    protected $bat = null;

    protected $collection = 'System\\Test\\ChildCollection';

    /**
     * @param string $baz
     * @return $this
     */
    public function setBaz($baz)
    {
        $this->baz = $baz;
        $this->addModifiedProperty('baz');
        return $this;
    }

    /**
     * @return string
     */
    public function getBaz()
    {
        return $this->baz;
    }

    /**
     * @param string $bat
     * @return $this
     */
    public function setBat($bat)
    {
        $this->bat = $bat;
        $this->addModifiedProperty('bat');
        return $this;
    }

    /**
     * @return string
     */
    public function getBat()
    {
        return $this->bat;
    }

    /**
     * @param \SprykerFeature\Shared\System\Transfer\Test\ChildCollection $collection
     * @return $this
     */
    public function setCollection(\SprykerFeature\Shared\System\Transfer\Test\ChildCollection $collection)
    {
        $this->collection = $collection;
        $this->addModifiedProperty('collection');
        return $this;
    }

    /**
     * @return \SprykerFeature\Shared\System\Transfer\Test\Child[]|\SprykerFeature\Shared\System\Transfer\Test\ChildCollection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param \SprykerFeature\Shared\System\Transfer\Test\Child $collection
     * @return \SprykerFeature\Shared\System\Transfer\Test\ChildCollection
     */
    public function addCollection(\SprykerFeature\Shared\System\Transfer\Test\Child $collection)
    {
        $this->collection->add($collection);
        return $this;
    }

    /**
     * @param \SprykerFeature\Shared\System\Transfer\Test\Child $collection
     * @return \SprykerFeature\Shared\System\Transfer\Test\ChildCollection
     */
    public function removeCollection(\SprykerFeature\Shared\System\Transfer\Test\Child $collection)
    {
        $this->collection->remove($collection);
        return $this;
    }


}
