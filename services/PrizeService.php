<?php

namespace app\services;

use app\models\forms\prize\PrizeFormInterface;
use app\models\User;
use app\models\UserPrize;
use app\services\prize\GiftPrize;
use app\services\prize\LoyaltyPointPrize;
use app\services\prize\MoneyPrize;
use app\services\prize\PrizeFactoryInterface;
use yii\base\BaseObject;

/**
 * Class PrizeService
 * @package app\services
 */
class PrizeService extends BaseObject
{
    /**
     * @param User $user
     * @return PrizeFormInterface|null
     */
    public function playOut(User $user): ?PrizeFormInterface
    {
        $prizeTypes = $this->getAllowedPrizes();
        $randKey = mt_rand(0, array_key_last($prizeTypes));

        if (!array_key_exists($randKey, $prizeTypes)) {
            $randKey = array_key_first($prizeTypes);
        }

        $checkedKeys = [$randKey];
        $prize = null;
        /** @var PrizeFactoryInterface $prizeType */
        $prizeType = new $prizeTypes[$randKey]($user);

        while (count($checkedKeys) < count($prizeTypes)) {
            try {
                $prize = $prizeType->playOut();

                if ($prize === null) {
                    throw new \Exception('Error play out');
                }

                break;
            } catch (\Exception $e) {
                foreach ($prizeTypes as $key => $class) {
                    if (!in_array($key, $checkedKeys)) {
                        $prizeType = new $class($user);
                        $checkedKeys[] = $key;
                        break;
                    }
                }

                if (count($checkedKeys) >= count($this->getAllowedPrizes())) {
                    break;
                }
            }
        }

        if ($prize === null || $prize->hasErrors()) {
            return null;
        }

        return $prize;
    }

    /**
     * @param UserPrize $userPrize
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function reward(UserPrize $userPrize): bool
    {
        return $userPrize->getEntity()->reward($userPrize);
    }

    /**
     * @param UserPrize $userPrize
     * @return bool
     */
    public function refuse(UserPrize $userPrize): bool
    {
        $userPrize->status = UserPrize::STATUS_REFUSE;
        return $userPrize->save();
    }

    /**
     * @param UserPrize $userPrize
     * @return bool
     */
    public function convert(UserPrize $userPrize): bool
    {
        $entity = $userPrize->getEntity();
        $result = $entity->convert($userPrize);

        if ($result === null) {
            return false;
        }

        $data = json_decode($userPrize->data, true);
        $data['old_amount'] = $data['amount'] ?? null;
        $data['amount'] = $result;
        $data[$entity::CURRENCY_ATTRIBUTE_NAME] = $entity::CURRENCY_LOYALTY_POINT;
        $userPrize->data = json_encode($data);

        return $userPrize->save();
    }

    /**
     * @return array
     */
    private function getAllowedPrizes(): array
    {
        return [
            MoneyPrize::class,
            LoyaltyPointPrize::class,
            GiftPrize::class,
        ];
    }
}
