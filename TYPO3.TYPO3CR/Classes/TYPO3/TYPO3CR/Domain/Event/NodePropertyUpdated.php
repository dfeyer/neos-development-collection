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
 * NodePropertyUpdated
 */
class NodePropertyUpdated implements EventInterface
{
    /**
     * @var string
     */
    protected $propertyName;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var mixed
     */
    protected $previousValue;

    /**
     * @param string $propertyName
     * @param mixed $value
     * @param mixed $previousValue
     */
    public function __construct(string $propertyName, $value, $previousValue)
    {
        $this->propertyName = $propertyName;
        $this->value = $value;
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
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getPreviousValue()
    {
        return $this->previousValue;
    }
}
