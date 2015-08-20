<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model;

class EventJournal
{

    /**
     * @var EventInterface
     */
    private $eventTemplate;

    /**
     * @var DataCollectorInterface[]
     */
    private $dataCollectors;

    /**
     * @param EventInterface $eventTemplate
     * @param DataCollectorInterface[] $defaultDataCollectors
     */
    public function __construct(EventInterface $eventTemplate, array $defaultDataCollectors)
    {
        $this->eventTemplate = $eventTemplate;
        foreach ($defaultDataCollectors as $dataCollector) {
            $this->addDataCollector($dataCollector);
        }
    }

    /**
     * @param $name
     *
     * @return EventInterface
     */
    public function createEvent($name)
    {
        $event = clone $this->eventTemplate;
        $event->addField(EventInterface::FIELD_EVENT_NAME, $name);
        $this->applyCollectors($event);

        return $event;
    }

    /**
     * @param DataCollectorInterface $dataCollector
     */
    public function addDataCollector(DataCollectorInterface $dataCollector)
    {
        $this->dataCollectors[get_class($dataCollector)] = $dataCollector;
    }

    /**
     * @param EventInterface $event
     */
    private function applyCollectors(EventInterface $event)
    {
        foreach ($this->dataCollectors as $collector) {
            $event->addFields($collector->getData());
        }
    }

    /**
     * @param EventInterface $event
     */
    public function saveEvent(EventInterface $event) {
        // foreach writers as writer ... ->write($event))
    }
}
