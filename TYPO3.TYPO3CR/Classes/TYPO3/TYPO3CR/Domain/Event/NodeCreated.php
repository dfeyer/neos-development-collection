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
use TYPO3\TYPO3CR\Domain\Model\NodeType;

/**
 * NodeCreated
 */
class NodeCreated implements EventInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $nodeType;

    /**
     * @var string|null
     */
    protected $identifier;

    /**
     * NodeCreated constructor.
     * @param string $name
     * @param null|string $nodeType
     * @param null|string $identifier
     */
    public function __construct(string $name, string $nodeType = null, string $identifier = null)
    {
        $this->name = $name;
        $this->nodeType = $nodeType;
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getNodeType()
    {
        return $this->nodeType;
    }

    /**
     * @return null|string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
}
