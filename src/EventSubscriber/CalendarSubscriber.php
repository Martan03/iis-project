<?php

namespace App\EventSubscriber;

use App\Repository\WalkRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private WalkRepository $wr;
    private UrlGeneratorInterface $router;

    public function __construct(
        WalkRepository $wr,
        UrlGeneratorInterface $router
    )
    {
        $this->wr = $wr;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        $walks = $this->wr->findAllFilter($filters['id'], $start, $end);
        foreach ($walks as $walk)
        {
            $walkEvent = new Event('Walk', $walk->getStart(), $walk->getEnd());

            $walkEvent->addOption('url', $this->router->generate('walk', [
                'id' => $walk->getId(),
            ]));

            $calendar->addEvent($walkEvent);
        }

        $calendar->addEvent(new Event(
            'Event 1',
            new \DateTime('Tuesday this week'),
            new \DateTime('Wednesdays this week')
        ));

        // If the end date is null or not defined, it creates a all day event
        $calendar->addEvent(new Event(
            'All day event',
            new \DateTime('Friday this week')
        ));
    }
}
