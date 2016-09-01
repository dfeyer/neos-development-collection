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
 * NodeWorkspaceSwitched
 */
class NodeWorkspaceSwitched implements EventInterface
{
    /**
     * @var string
     */
    protected $workspace;

    /**
     * @var string|null
     */
    protected $previousWorspace;

    /**
     * NodeWorkspaceSwitched constructor.
     * @param string $workspace
     * @param string $previousWorspace
     */
    public function __construct(string $workspace, string $previousWorspace = null)
    {
        $this->workspace = $workspace;
        $this->previousWorspace = $previousWorspace;
    }

    /**
     * @return string
     */
    public function getWorkspace(): string
    {
        return $this->workspace;
    }

    /**
     * @return null|string
     */
    public function getPreviousWorspace()
    {
        return $this->previousWorspace;
    }
}
