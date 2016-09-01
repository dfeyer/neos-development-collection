<?php
namespace TYPO3\TYPO3CR\Domain\Event;

/*
 * This file is part of the TYPO3.TYPO3CR package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Cqrs\Event\EventInterface;
use TYPO3\Flow\Annotations as Flow;

/**
 * AbstractNodeCopy
 */
abstract class AbstractNodeCopy implements EventInterface
{
    /**
     * @var string
     */
    protected $identifier;
    /**
     * @var string
     */
    protected $referenceNode;

    /**
     * @var null|string
     */
    protected $nodeName;

    /**
     * @param string $identifier
     * @param string $referenceNode
     * @param null|string $nodeName
     */
    public function __construct(string $identifier, string $referenceNode, string $nodeName = null)
    {
        $this->identifier = $identifier;
        $this->referenceNode = $referenceNode;
        $this->nodeName = $nodeName;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getReferenceNode(): string
    {
        return $this->referenceNode;
    }

    /**
     * @return null|string
     */
    public function getNodeName()
    {
        return $this->nodeName;
    }
}
