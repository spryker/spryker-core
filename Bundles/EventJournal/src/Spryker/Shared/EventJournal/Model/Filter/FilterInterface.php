<?php
/**
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace Spryker\Shared\EventJournal\Model\Filter;

use Spryker\Shared\EventJournal\Model\EventInterface;

interface FilterInterface
{

    /**
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
     *
     * @return void
     */
    public function filter(EventInterface $event);

    /**
     * @return string
     */
    public function getType();

}
