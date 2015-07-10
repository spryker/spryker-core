<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication;

use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Symfony\Component\Validator\Validator;

class UiDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @param $filterValue
     *
     * @return array
     */
    public function getDateTimeColumnFormats($filterValue)
    {
        return [
            $this->getCalendarWeekFormat($filterValue),
            $this->getCalendarWordFormat($filterValue),
            $this->getHumanDateFormat($filterValue),
            $this->getDateTimeStringFormat($filterValue),
        ];
    }

    /**
     * @param $filterValue
     *
     * @return object
     */
    public function getCalendarWeekFormat($filterValue)
    {
        $calendarWeek = $this->getFactory()->create(
            'Grid\\DateTimeColumn\\CalendarWeek',
            $filterValue
        );

        return $calendarWeek;
    }

    /**
     * @param $filterValue
     *
     * @return object
     */
    public function getCalendarWordFormat($filterValue)
    {
        $calendarWord = $this->getFactory()->create(
            'Grid\\DateTimeColumn\\CalendarWord',
            $filterValue
        );

        return $calendarWord;
    }

    /**
     * @param $filterValue
     *
     * @return object
     */
    public function getHumanDateFormat($filterValue)
    {
        $humanDate = $this->getFactory()->create(
            'Grid\\DateTimeColumn\\HumanDate',
            $filterValue
        );

        return $humanDate;
    }

    /**
     * @param $filterValue
     *
     * @return object
     */
    public function getDateTimeStringFormat($filterValue)
    {
        $dateTimeString = $this->getFactory()->create(
            'Grid\\DateTimeColumn\\DateTimeString',
            $filterValue
        );

        return $dateTimeString;
    }

    /**
     * @return Validator
     */
    public function getValidator()
    {
        return $this->getLocator()->application()->pluginPimple()->getApplication()['validator'];
    }

}
