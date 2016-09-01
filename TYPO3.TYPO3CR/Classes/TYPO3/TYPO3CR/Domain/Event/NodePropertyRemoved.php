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
 * NodePropertyRemoved
 */
class NodePropertyRemoved implements EventInterface
{
    /**
     * @var string
     */
    protected $propertyName;

    /**
     * @var mixed
     */
    protected $previousValue;

    /**
     * @param string $propertyName
     * @param mixed $previousValue
     */
    public function __construct(string $propertyName, $previousValue)
    {
        $this->propertyName = $propertyName;
        $this->previousValue = $previousValue;
    }

    /**
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    /**
     * @return mixed
     */
    public function getPreviousValue()
    {
        return $this->previousValue;
    }
}
