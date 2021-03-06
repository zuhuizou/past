<?php
declare(strict_types=1);
namespace ParagonIE\PAST\Rules;

use ParagonIE\PAST\Exception\PastException;
use ParagonIE\PAST\{
    JsonToken,
    ValidationRuleInterface
};

/**
 * Class NotExpired
 * @package ParagonIE\PAST\Rules
 */
class NotExpired implements ValidationRuleInterface
{
    /** @var string $failure */
    protected $failure = 'OK';

    /** @var \DateTime $now */
    protected $now;

    /**
     * NotExpired constructor.
     * @param \DateTime|null $now Allows "now" to be overwritten for unit testing
     */
    public function __construct(\DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        $this->now = $now;
    }
    /**
     * @return string
     */
    public function getFailureMessage(): string
    {
        return $this->failure;
    }

    /**
     * @param JsonToken $token
     * @return bool
     */
    public function isValid(JsonToken $token): bool
    {
        try {
            $expires = $token->getExpiration();
            if ($expires < $this->now) {
                $this->failure = 'This token has expired.';
                return false;
            }
        } catch (PastException $ex) {
            $this->failure = $ex->getMessage();
            return false;
        }
        return true;
    }
}
