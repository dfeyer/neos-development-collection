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
 * AbstractNodeMoved
 */
abstract class AbstractNodeMoved implements EventInterface
{
    /**
     * @var string
     */
    protected $referenceNode;

    /**
     * @var null|string
     */
    protected $newName;

    /**
     * @param string $referenceNode
     * @param null|string $newName
     */
    public function __construct(string $referenceNode, string $newName = null)
    {
        $this->referenceNode = $referenceNode;
        $this->newName = $newName;
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
    public function getNewName()
    {
        return $this->newName;
    }
}
