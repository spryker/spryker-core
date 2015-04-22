<?php
namespace SprykerFeature\Zed\Library\Import;

interface FilterableProcessInterface
{
    /**
     * @return callable
     */
    public function getFilter();
}
