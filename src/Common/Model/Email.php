<?php

namespace AlephTools\DDD\Common\Model;

use AlephTools\DDD\Common\Infrastructure\Sanitizer;
use AlephTools\DDD\Common\Infrastructure\ValueObject;

/**
 * @property-read string $address
 */
class Email extends ValueObject
{
    public const ADDRESS_MAX_LENGTH = 255;

    protected $address;

    protected static $emailPattern = '/[_A-Za-z0-9-]+(.[_A-Za-z0-9-]+)*@[A-Za-z0-9]+(.[A-Za-z0-9]+)*(.[A-Za-z]{2,})/';

    /**
     * Constructor. Available formats:
     * Email()
     * Email(string $address)
     * Email(array $properties)
     *
     * @param array|string|null $address
     */
    public function __construct($address = null)
    {
        if (is_array($address)) {
            parent::__construct($address);
        } else {
            parent::__construct(['address' => $address]);
        }
    }

    protected function setAddress(?string $address): void
    {
        $this->address = Sanitizer::sanitizeEmail($address);
    }

    protected function validateAddress(): void
    {
        $this->assertArgumentMaxLength(
            $this->address,
            static::ADDRESS_MAX_LENGTH,
            'Email address must be at most ' . static::ADDRESS_MAX_LENGTH . ' characters.'
        );
        if (strlen($this->address) > 0) {
            $this->assertArgumentPatternMatch($this->address, self::$emailPattern, 'Invalid email format.');
        }
    }
}