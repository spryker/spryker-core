<?php

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Business\Model;

interface HelperInterface
{

    /**
     * @param array $entity
     *
     * @return string
     */
    public function organizeData(array $entity);

    /**
     * @param array $entity
     *
     * @return int
     */
    public function getDefaultPrice(array $entity);

    /**
     * @param array $entity
     *
     * @return bool
     */
    public function hasDefaultPrice(array $entity);

    /**
     * @return string
     */
    public function getDefaultPriceType();

}
