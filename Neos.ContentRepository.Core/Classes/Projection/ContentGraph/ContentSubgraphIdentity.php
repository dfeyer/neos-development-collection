<?php

namespace Neos\ContentRepository\Core\Projection\ContentGraph;

use Neos\ContentRepository\Core\DimensionSpace\DimensionSpacePoint;
use Neos\ContentRepository\Core\SharedModel\ContentRepository\ContentRepositoryId;
use Neos\ContentRepository\Core\SharedModel\Node\NodeAddress;
use Neos\ContentRepository\Core\SharedModel\Workspace\ContentStreamId;

/**
 * This describes a node's read model identity parts which are rooted in the {@see ContentSubgraphInterface}, namely:
 * - {@see ContentRepositoryId}
 * - {@see ContentStreamId}
 * - {@see dimensionSpacePoint}
 * - {@see visibilityConstraints}
 *
 * In addition to the above subgraph identity, a Node's read model identity is further described
 * by {@see Node::$aggregateId}.
 *
 * With the above information, you can fetch a Subgraph directly by using
 * {@see \Neos\ContentRepositoryRegistry\ContentRepositoryRegistry::subgraphForNode()}.
 *
 * (a bit lower-level): For Non-Neos/Flow installations, you can fetch a Subgraph using
 * {@see ContentGraphInterface::getSubgraph()}.
 *
 * ## A note about the referenced DimensionSpacePoint
 *
 * This is the DimensionSpacePoint this node has been accessed in, and NOT the DimensionSpacePoint where the
 * node is "at home".
 * The DimensionSpacePoint where the node is (at home) is called the ORIGIN DimensionSpacePoint,
 * and this can be accessed using {@see Node::originDimensionSpacePoint}. If in doubt, you'll
 * usually need this method instead of the Origin DimensionSpacePoint inside the read model; and you'll
 * need the OriginDimensionSpacePoint when constructing commands on the write side.
 *
 * @deprecated please use the {@see NodeAddress} instead {@see Node::$subgraphIdentity}. Will be removed before the Final Neos 9 release.
 * @internal
 */
final readonly class ContentSubgraphIdentity implements \JsonSerializable
{
    private function __construct(
        public ContentRepositoryId $contentRepositoryId,
        public ContentStreamId $contentStreamId,
        /**
         * DimensionSpacePoint a node has been accessed in.
         */
        public DimensionSpacePoint $dimensionSpacePoint,
        public VisibilityConstraints $visibilityConstraints,
    ) {
    }

    public static function create(
        ContentRepositoryId $contentRepositoryId,
        ContentStreamId $contentStreamId,
        DimensionSpacePoint $dimensionSpacePoint,
        VisibilityConstraints $visibilityConstraints
    ): self {
        return new self(
            $contentRepositoryId,
            $contentStreamId,
            $dimensionSpacePoint,
            $visibilityConstraints
        );
    }

    public function equals(ContentSubgraphIdentity $other): bool
    {
        return $this->contentRepositoryId->equals($other->contentRepositoryId)
            && $this->contentStreamId->equals($other->contentStreamId)
            && $this->dimensionSpacePoint->equals($other->dimensionSpacePoint)
            && $this->visibilityConstraints->getHash() === $other->visibilityConstraints->getHash();
    }

    public function withContentStreamId(ContentStreamId $contentStreamId): self
    {
        return new self(
            $this->contentRepositoryId,
            $contentStreamId,
            $this->dimensionSpacePoint,
            $this->visibilityConstraints
        );
    }

    public function withVisibilityConstraints(VisibilityConstraints $visibilityConstraints): self
    {
        return new self(
            $this->contentRepositoryId,
            $this->contentStreamId,
            $this->dimensionSpacePoint,
            $visibilityConstraints
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            'contentRepositoryId' => $this->contentRepositoryId,
            'contentStreamId' => $this->contentStreamId,
            'dimensionSpacePoint' => $this->dimensionSpacePoint,
            'visibilityConstraints' => $this->visibilityConstraints
        ];
    }
}
