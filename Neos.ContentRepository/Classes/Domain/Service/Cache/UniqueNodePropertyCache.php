<?php
namespace Neos\ContentRepository\Domain\Service\Cache;

/*
 * This file is part of the Neos.ContentRepository package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Cache\Frontend\StringFrontend;
use Neos\Flow\Annotations as Flow;

/**
 * UniqueNodePropertyCache
 */
final class UniqueNodePropertyCache
{
    /**
     * @var StringFrontend
     * @Flow\Inject
     */
    protected $cache;

    /**
     * @param string $entryIdentifier
     * @return bool
     */
    public function has(string $entryIdentifier): bool
    {
        return $this->cache->has($entryIdentifier);
    }

    /**
     * @param string $entryIdentifier
     * @param mixed $value
     */
    public function set(string $entryIdentifier, $value)
    {
        $this->cache->set($entryIdentifier, $value);
    }

    /**
     * @param string $entryIdentifier
     */
    public function get(string $entryIdentifier)
    {
        $this->cache->get($entryIdentifier);
    }

}
