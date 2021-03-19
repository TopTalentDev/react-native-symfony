<?php
/*
 * This file has been automatically generated by doctrine.
 * You can edit this file as it will not be overwritten.
 */

declare(strict_types=1);

namespace App\Model;

use App\Enums\StatusEnum;
use App\Model\Generated\AbstractPromotion;
use doctrine\GraphQLite\Annotations\Type;
use function Safe\substr;
use function Safe\usort;

/**
 * The Promotion class maps the 'promotions' table in database.
 * @Type
 */
class Promotion extends AbstractPromotion
{

    /**
     * The constructor takes all compulsory arguments.
     *
     * @param \App\Model\Business $business
     */
    public function __construct(\App\Model\Business $business)
    {
        parent::__construct($business);
        $digits = 7;
        $randomString = substr(str_shuffle(str_repeat($x = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ', (int)ceil($digits / strlen($x)))), 1, $digits);
        $this->setUuid($randomString);
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isActive(): bool
    {
        $now = new \DateTimeImmutable();
        return $this->getStatus() === StatusEnum::APPROVED
            && $this->getRefers()->count() < $this->getTargetNumber()
            && $this->getStartDate() && $this->getStartDate() <= $now
            && ($this->getIsOnlinePromo() || ($this->getEndDate() && $this->getEndDate() >= $now));
    }

    /**
     * @param Promotion[] $promotions
     * @param bool $desc
     * @return Promotion[]
     * @throws \Safe\Exceptions\ArrayException
     */
    public static function sortByCreatedDate(array $promotions, bool $desc = true): array
    {
        // Ordering
        if (!$desc) {
            // asc
            usort($promotions, static function (Promotion $a, Promotion $b) {
                return $a->getCreatedDate()->getTimestamp() - $b->getCreatedDate()->getTimestamp();
            });
        } else {
            // desc
            usort($promotions, static function (Promotion $a, Promotion $b) {
                return $b->getCreatedDate()->getTimestamp() - $a->getCreatedDate()->getTimestamp();
            });
        }
        return $promotions;
    }
}