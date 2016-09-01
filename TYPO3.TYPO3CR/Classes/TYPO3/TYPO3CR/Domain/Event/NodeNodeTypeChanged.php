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
 * NodeNodeTypeChanged
 */
class NodeNodeTypeChanged implements EventInterface
{
    /**
     * @var string
     */
    protected $nodeType;

    /**
     * @var string
     */
    protected $previousNodeType;

    /**
     * NodeNodeTypeChanged constructor.
     * @param string $nodeType
     * @param string $previousNodeType
     */
    public function __construct($nodeType, $previousNodeType)
    {
        $this->nodeType = $nodeType;
        $this->previousNodeType = $previousNodeType;
    }

    /**
     * @return string
     */
    public function getNodeType(): string
    {
        return $this->nodeType;
    }

    /**
     * @return string
     */
    public function getPreviousNodeType(): string
    {
        return $this->previousNodeType;
    }
}
