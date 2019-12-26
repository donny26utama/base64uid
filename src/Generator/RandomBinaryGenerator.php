<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Base64UID\Generator;

use GpsLab\Component\Base64UID\BitmapEncoder\BitmapEncoder;
use GpsLab\Component\Base64UID\Exception\InvalidArgumentException;

class RandomBinaryGenerator implements BinaryGenerator, Generator
{
    /**
     * @var BitmapEncoder
     */
    private $encoder;

    /**
     * @var int
     */
    private $uid_bitmap_length;

    /**
     * @param BitmapEncoder $encoder
     * @param int           $uid_bitmap_length
     */
    public function __construct(BitmapEncoder $encoder, $uid_bitmap_length)
    {
        if (!is_int($uid_bitmap_length)) {
            throw new InvalidArgumentException(sprintf('Length of bitmap for UID should be integer, got "%s" instead.', gettype($uid_bitmap_length)));
        }
        if ($uid_bitmap_length <= 0) {
            throw new InvalidArgumentException(sprintf('Length of bitmap for UID should be grate then 0, got "%d" instead.', $uid_bitmap_length));
        }
        if ($uid_bitmap_length > PHP_INT_SIZE * 8) {
            throw new InvalidArgumentException(sprintf('Length of bitmap for UID should be less than or equal to "%d", got "%d" instead.', PHP_INT_SIZE * 8, $uid_bitmap_length));
        }

        $this->encoder = $encoder;
        $this->uid_bitmap_length = $uid_bitmap_length;
    }

    /**
     * @return int
     */
    public function generateBitmap()
    {
        $uid = 0;
        for ($i = 0; $i < $this->uid_bitmap_length; ++$i) {
            if (random_int(0, 1)) {
                $uid |= 1 << $i;
            }
        }

        return $uid;
    }

    /**
     * @return string
     */
    public function generate()
    {
        return $this->encoder->encoder($this->generateBitmap());
    }
}
