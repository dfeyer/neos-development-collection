<?php
namespace Neos\ContentRepository\Domain\Utility;

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
 * BloomFilter
 */
final class BloomFilter implements \Serializable
{
    /**
     * @var int
     */
    private $maximumSize;

    /**
     * @var float
     */
    private $probability;

    /**
     * @var int
     */
    private $space;

    /**
     * @var array
     */
    private $hashes;

    /**
     * @var string
     */
    private $filter;

    /**
     * @var array
     */
    private $hashAlgos;

    /**
     * @var bool
     */
    private $empty = true;

    /**
     * @var bool
     */
    private $useBcMath = false;

    /**
     * @param int $maximumSize Maximum number of elements you wish to store.
     * @param float $probability False positive probability you wish to achieve.
     */
    protected function __construct(int $maximumSize, float $probability)
    {
        $this->maximumSize = $maximumSize;
        $this->probability = $probability;

        $this->initialize();

        $this->filter = str_repeat("\0", ceil($this->space / 8));
    }

    /**
     * @param int $maximumSize
     * @param float $probability
     * @return BloomFilter
     */
    public static function create(int $maximumSize = 64, float $probability = 0.001): BloomFilter
    {
        return new static($maximumSize, $probability);
    }

    /**
     * @param string $value
     * @return BloomFilter
     */
    public static function createFromSerilizedValue(string $value): BloomFilter
    {
        $filter = static::create();
        $filter->unserialize($value);
        return $filter;
    }

    /**
     * Set element in the filter
     *
     * @param mixed $element
     */
    public function add($element)
    {
        $element = $this->normalizeElement($element);
        $hashes = $this->hash($element);
        foreach ($hashes as $hash) {
            $offset = (int)floor($hash / 8);
            $bit = (int)($hash % 8);
            $this->filter[$offset] = chr(ord($this->filter[$offset]) | (2 ** $bit));
        }
        $this->empty = false;
    }

    /**
     * Is element in the hash
     *
     * Beware that a strict false means strict false, while a strict true
     * means "probably with a X% probably" where X is the value you built
     * the filter with.
     *
     * @param mixed $element
     *
     * @return boolean
     */
    public function contains($element): bool
    {
        $element = $this->normalizeElement($element);
        $hashes = $this->hash($element);
        foreach ($hashes as $hash) {
            $offset = (int)floor($hash / 8);
            $bit = (int)($hash % 8);
            if (!(ord($this->filter[$offset]) & (2 ** $bit))) {
                return false;
            }
        }
        return true;
    }

    /**
     * Is this instance empty
     *
     * @return boolean
     */
    public function isEmpty(): bool
    {
        return $this->empty;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return implode(',', [$this->maximumSize, $this->probability, base64_encode(gzcompress($this->filter))]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($maximumSize, $probability, $filter) = explode(',', $serialized, 3);
        $this->maximumSize = (int)$maximumSize;
        $this->probability = (float)$probability;
        $this->filter = gzuncompress(base64_decode($filter));
        $this->initialize();
        $this->empty = false;
    }

    /**
     * @return void
     */
    private function initialize()
    {
        $this->space = $this->calculateSpace($this->maximumSize, $this->probability);
        $this->hashes = $this->calculateHashFunctions($this->maximumSize, $this->space);
        $this->hashAlgos = $this->getHashAlgos();
        if ($this->hashes > $this->numHashFunctionsAvailable($this->hashAlgos)) {
            throw new \LogicException("Can't initialize filter with available hash functions");
        }
        if (!function_exists('gmp_init')) {
            if (!function_exists('bcmod')) {
                throw new \LogicException("Can't initialize filter if you don't have any of the 'gmp' or 'bcmath' extension (gmp is faster)");
            }
            $this->useBcMath = true;
        }
    }

    /**
     * @return array
     */
    private function getHashAlgos(): array
    {
        return hash_algos();
    }

    /**
     * @param int $maximumSize
     * @param float $probability
     * @return int
     */
    private function calculateSpace(int $maximumSize, float $probability): int
    {
        return (int)ceil(($maximumSize * (log($probability)) / (log(2) ** 2)) * -1);
    }

    /**
     * @param int $maximumSize
     * @param int $space
     * @return int
     */
    private function calculateHashFunctions(int $maximumSize, int $space): int
    {
        return (int)ceil($space / $maximumSize * log(2));
    }

    /**
     * @param array $hashAlgos
     * @return int
     */
    private function numHashFunctionsAvailable(array $hashAlgos): int
    {
        $num = 0;
        foreach ($hashAlgos as $algo) {
            $num += count(unpack('J*', hash($algo, 'bloom', true)));
        }
        return $num;
    }

    /**
     * @param mixed $element
     * @return array
     */
    private function hash($element): array
    {
        $hashes = [];
        foreach ($this->hashAlgos as $algo) {
            foreach (unpack('P*', hash($algo, $element, true)) as $hash) {
                if ($this->useBcMath) {
                    $hashes[] = bcmod(sprintf("%u", $hash), $this->space);
                } else {
                    $hash = gmp_init(sprintf("%u", $hash));
                    $hashes[] = ($hash % $this->space);
                }
                if (count($hashes) >= $this->hashes) {
                    break 2;
                }
            }
        }
        return $hashes;
    }

    private function normalizeElement($element)
    {
        if (!is_scalar($element)) {
            $element = serialize($element);
        }
        return $element;
    }
}
