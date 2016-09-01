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
 * NodeNameUpdated
 */
class NodeNameUpdated implements EventInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $previousName;

    /**
     * @param string $name
     */
    public function __construct(string $name, string $previousName = null)
    {
        $this->name = $name;
        $this->previousName = $previousName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPreviousName(): string
    {
        return $this->previousName;
    }
}
