<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Invoicing;

use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractContainer;

class ItemContainer extends AbstractContainer
{

    /**
     * @var string
     */
    protected $id;
    /**
     * @var int
     */
    protected $pr;
    /**
     * @var int
     */
    protected $no;
    /**
     * @var string
     */
    protected $de;
    /**
     * Artikeltyp (Enum)
     *
     * @var string
     */
    protected $it;
    /**
     * @var int
     */
    protected $va;
    /**
     * DeliveryDate (YYYYMMDD)
     *
     * @var string
     */
    protected $sd;
    /**
     * Lieferzeitraums-Ende (YYYYMMDD)
     *
     * @var string
     */
    protected $ed;

    /**
     * @param int $key
     *
     * @return array
     */
    public function toArrayByKey($key)
    {
        $data = [];
        if (isset($this->id)) $data['id[' . $key . ']'] = $this->getId();
        if (isset($this->pr)) $data['pr[' . $key . ']'] = $this->getPr();
        if (isset($this->no)) $data['no[' . $key . ']'] = $this->getNo();
        if (isset($this->de)) $data['de[' . $key . ']'] = $this->getDe();
        if (isset($this->it)) $data['it[' . $key . ']'] = $this->getIt();
        if (isset($this->va)) $data['va[' . $key . ']'] = $this->getVa();
        if (isset($this->sd)) $data['sd[' . $key . ']'] = $this->getSd();
        if (isset($this->ed)) $data['ed[' . $key . ']'] = $this->getEd();

        return $data;
    }

    /**
     * @param string $de
     */
    public function setDe($de)
    {
        $this->de = $de;
    }

    /**
     * @return string
     */
    public function getDe()
    {
        return $this->de;
    }

    /**
     * @param string $ed
     */
    public function setEd($ed)
    {
        $this->ed = $ed;
    }

    /**
     * @return string
     */
    public function getEd()
    {
        return $this->ed;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $no
     */
    public function setNo($no)
    {
        $this->no = $no;
    }

    /**
     * @return int
     */
    public function getNo()
    {
        return $this->no;
    }

    /**
     * @param int $pr
     */
    public function setPr($pr)
    {
        $this->pr = $pr;
    }

    /**
     * @return int
     */
    public function getPr()
    {
        return $this->pr;
    }

    /**
     * @param string $sd
     */
    public function setSd($sd)
    {
        $this->sd = $sd;
    }

    /**
     * @return string
     */
    public function getSd()
    {
        return $this->sd;
    }

    /**
     * @param int $va
     */
    public function setVa($va)
    {
        $this->va = $va;
    }

    /**
     * @return int
     */
    public function getVa()
    {
        return $this->va;
    }

    /**
     * @param string $it
     */
    public function setIt($it)
    {
        $this->it = $it;
    }

    /**
     * @return string
     */
    public function getIt()
    {
        return $this->it;
    }

}
