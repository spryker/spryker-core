<?php

/*
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Shared\EventJournal\Model\Collector;

interface DataCollectorInterface
{

    /**
     * @return array
     */
    public function getData();

    /**
     * @return string
     */
    public function getType();

}
