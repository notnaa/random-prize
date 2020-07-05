<?php

namespace app\models\forms\prize;

use app\models\User;
use app\models\UserPrize;

/**
 * Interface PrizeFormInterface
 * @package app\models\forms\prize
 */
interface PrizeFormInterface
{
    /**
     * @return string|int
     */
    public function getPrize();

    /**
     * @return int
     */
    public function getCurrency(): int;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return User
     */
    public function getUser(): User;

    /**
     * @param UserPrize $userPrize
     * @return bool
     */
    public function loadUserPrize(UserPrize $userPrize): bool;

    /**
     * @return string
     */
    public function getFormattedText(): string;

    /**
     * @param UserPrize $userPrize
     */
    public function setModel(UserPrize $userPrize);

    /**
     * @return UserPrize|null
     */
    public function getModel(): ?UserPrize;
}
