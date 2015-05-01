<?php

namespace SprykerFeature\Zed\Payone\Business\Api\Request\Container\Invoicing;
use SprykerFeature\Zed\Payone\Business\Api\Request\Container\AbstractContainer;


class ItemContainer extends AbstractContainer
{

    /**
     * @var string
     */
    protected $id = null;
    /**
     * @var int
     */
    protected $pr = null;
    /**
     * @var int
     */
    protected $no = null;
    /**
     * @var string
     */
    protected $de = null;
    /**
     * Artikeltyp (Enum)
     * @var string
     */
    protected $it = null;
    /**
     * @var int
     */
    protected $va = null;
    /**
     * DeliveryDate (YYYYMMDD)
     * @var string
     */
    protected $sd = null;
    /**
     * Lieferzeitraums-Ende (YYYYMMDD)
     * @var string
     */
    protected $ed = null;


    /**
     * @param int $key
     * @return array
     */
    public function toArrayByKey($key)
    {
        $data = array();
        $data['id[' . $key . ']'] = $this->getId();
        $data['pr[' . $key . ']'] = $this->getPr();
        $data['no[' . $key . ']'] = $this->getNo();
        $data['de[' . $key . ']'] = $this->getDe();
        $data['it[' . $key . ']'] = $this->getIt();
        $data['va[' . $key . ']'] = $this->getVa();
        $data['sd[' . $key . ']'] = $this->getSd();
        $data['ed[' . $key . ']'] = $this->getEd();
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
