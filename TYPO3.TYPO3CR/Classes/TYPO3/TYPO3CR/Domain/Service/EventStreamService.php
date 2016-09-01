<?php
namespace TYPO3\TYPO3CR\Domain\Service;

/*
 * This file is part of the TYPO3.TYPO3CR package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Cqrs\Event\EventBusInterface;
use Neos\Cqrs\Event\EventTransport;
use Neos\EventStore\Event\Metadata;
use Neos\EventStore\EventStoreInterface;
use Neos\EventStore\EventStream;
use Neos\EventStore\Exception\EventStreamNotFoundException;
use TYPO3\Flow\Annotations as Flow;

/**
 * EventStreamService
 *
 * @Flow\Scope("singleton")
 * @api
 */
class EventStreamService
{
    /**
     * @var EventStoreInterface
     * @Flow\Inject
     */
    protected $eventStore;

    /**
     * @var EventBusInterface
     * @Flow\Inject
     */
    protected $eventBus;

    /**
     * @param string $aggregateIdentifier
     * @param string $aggregateName
     * @param EventTransport[] $uncommitedEvents
     * @return void
     */
    public function commit(string $aggregateIdentifier, string $aggregateName, ...$uncommitedEvents)
    {
        try {
            $stream = $this->eventStore
                ->get($aggregateIdentifier);
        } catch (EventStreamNotFoundException $e) {
            $stream = new EventStream(
                $aggregateIdentifier,
                $aggregateName,
                []
            );
        } finally {
            $stream->addEvents(...$uncommitedEvents);
        }

        $version = $this->eventStore->commit($stream);

        /** @var EventTransport $eventTransport */
        foreach ($uncommitedEvents as $eventTransport) {
            // @todo metadata enrichment must be done in external service, with some middleware support
            $eventTransport->getMetaData()->add(Metadata::VERSION, $version);

            $this->eventBus->handle($eventTransport);
        }
    }
}
