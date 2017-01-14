<?php
namespace Neos\ContentRepository\Domain\Model;

/*
 * This file is part of the Neos.ContentRepository package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;

/**
 * UniqueNodeUriPathSegment
 */
final class UniqueNodeUriPathSegment extends AbstractUniqueNodeProperty
{
    /**
     * @param NodeInterface $node
     * @return string
     */
    protected function nodeUniqueProperty(NodeInterface $node)
    {
        return $node->getProperty('uriPathSegment');
    }
}
