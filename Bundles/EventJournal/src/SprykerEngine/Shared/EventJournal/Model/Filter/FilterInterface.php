<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerEngine\Shared\EventJournal\Model\Filter;

use SprykerEngine\Shared\EventJournal\Model\EventInterface;

interface FilterInterface
{

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function filter(EventInterface $event);

    /**
     * @return string
     */
    public function getType();
}
