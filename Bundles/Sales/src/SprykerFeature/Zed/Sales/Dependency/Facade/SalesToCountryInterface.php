<?php

namespace SprykerFeature\Zed\Sales\Dependency\Facade;

interface SalesToCountryInterface
{

    /**
     * @param string $iso2Code
     *
     * @return int
     */
    public function getIdCountryByIso2Code($iso2Code);

}
