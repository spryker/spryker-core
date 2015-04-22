<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Model;

/**
 * Class Product
 * @package SprykerFeature\Zed\Product\Business\Model
 */
class ConcreteProduct extends AbstractProduct
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $abstractProductSku;

    /**
     * @return string
     */
    public function getUrl()
    {
        if (!$this->url) {
            return '/' . urlencode(str_replace(' ', '-', $this->getName()));
        }

        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getAbstractProductSku()
    {
        return $this->abstractProductSku;
    }

    /**
     * @param int $abstractProductSku
     */
    public function setAbstractProductSku($abstractProductSku)
    {
        $this->abstractProductSku = $abstractProductSku;
    }
}
 