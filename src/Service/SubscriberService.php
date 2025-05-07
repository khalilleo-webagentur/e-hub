<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Subscriber;
use App\Entity\User;
use App\Helper\Job;
use App\Repository\SubscriberRepository;
use App\Traits\EmailAddressTrait;
use App\Traits\RandomTokenGeneratorTrait;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

final class SubscriberService
{
    use RandomTokenGeneratorTrait;
    use EmailAddressTrait;

    public function __construct(
        private readonly SubscriberRepository $subscriberRepository
    ){
    }

    public function getById(int $id): ?Subscriber
    {
        return $this->subscriberRepository->find($id);
    }

    public function getOneByUserAndId(UserInterface $user, int $id): ?Subscriber
    {
        return $this->subscriberRepository->findOneBy(['user' => $user, 'id' => $id]);
    }

    public function getByEmail(string $email): ?Subscriber
    {
        return $this->subscriberRepository->findOneBy(['email' => $email]);
    }

    public function getByToken(string $token): ?Subscriber
    {
        return $this->subscriberRepository->findOneBy(['token' => $token]);
    }

    /**
     * @return Subscriber[]
     */
    public function getAllByUser(UserInterface $user): array
    {
        return $this->subscriberRepository->findBy(['user' => $user]);
    }

    /**
     * @return Subscriber[]
     */
    public function getAll(): array
    {
        return $this->subscriberRepository->findAll();
    }

    public function getOneTheReceiveNewsletter(): ?Subscriber
    {
        return $this->subscriberRepository->findOneBy(['isSubscribed' => 1, 'hasReceived' => 0]);
    }

    /**
     * @return Subscriber[]
     */
    public function getActiveSubscribers(): array
    {
        return $this->subscriberRepository->findBy(['isSubscribed' => 1]);
    }

    /**
     * @return Subscriber[]
     */
    public function getActiveSubscribersByUser(UserInterface $user): array
    {
        return $this->subscriberRepository->findBy(['user' => $user ,'isSubscribed' => 1]);
    }

    /**
     * @return Subscriber[]
     */
    public function getUnActiveSubscribers(): array
    {
        return $this->subscriberRepository->findBy(['isSubscribed' => 0]);
    }

    /**
     * @return Subscriber[]
     */
    public function getSubscribedAndReceivedNewsletterByUser(UserInterface $user): array
    {
        return $this->subscriberRepository->findBy(['user' => $user, 'isSubscribed' => 1, 'hasReceived' => 1]);
    }

    /**
     * @return Subscriber[]
     */
    public function getAllByUserWithOffsetAndLimit(UserInterface $user, int $offset, int $limit): array
    {
        return $this->subscriberRepository->findAllByUserWithOffsetAndLimit($user, $offset, $limit);
    }

    /**
     * @return Subscriber[]
     */
    public function getAllWithOffsetAndLimit(int $offset, int $limit): array
    {
        return $this->subscriberRepository->findAllWithOffsetAndLimit($offset, $limit);
    }

    public function addNewSubscriber(string $email): ?Subscriber
    {
        if ($this->getByEmail($email)) {
            return null;
        }

        $entity = new Subscriber();
        $entity
            ->setName($this->getNameFromEmailAddress($email))
            ->setEmail($email)
            ->setToken($this->getRandomToken(32));

        $this->save($entity);

        return $entity;
    }

    public function updateUserSubscription(string $email): bool
    {
        $subscriber = $this->getByEmail($email);

        if ($subscriber) {
            $token = $this->getRandomToken(32);
            $this->updateToken($subscriber, $token);

            return $this->updateIsSubscribed($subscriber, true) !== null;
        }

        return false;
    }

    public function subscribeUser(Subscriber $subscriber): void
    {
        $this->updateIsSubscribed($subscriber, true);
    }

    public function unSubscribeUser(Subscriber $subscriber): void
    {
        $this->updateIsSubscribed($subscriber, false);
        $this->updateToken($subscriber, '');
    }

    public function updateIsSubscribed(Subscriber $subscriber, bool $isSubscribed): Subscriber
    {
        $this->save($subscriber->setIsSubscribed($isSubscribed));

        return $subscriber;
    }

    public function updateHasReceived(Subscriber $subscriber, bool $hasReceived): Subscriber
    {
        $this->save($subscriber->setHasReceived($hasReceived));

        return $subscriber;
    }

    public function resetReceivedForSubscribers(): void
    {
        foreach ($this->getActiveSubscribers() as $subscriber) {
            $this->updateHasReceived($subscriber, false);
        }
    }

    public function updateToken(Subscriber $subscriber, string $token): Subscriber
    {
        $this->save($subscriber->setToken($token));

        return $subscriber;
    }

    public function update(
        Subscriber $subscriber,
        string     $name,
        string     $email,
        string     $token,
        bool       $isSubscribed,
        bool       $received,
        string     $updated,
        string     $created
    ): Subscriber
    {

        $subscriber
            ->setName($name)
            ->setEmail($email)
            ->setToken($token)
            ->setIsSubscribed($isSubscribed)
            ->setHasReceived($received)
            ->setUpdatedAt(new DateTime($updated))
            ->setCreatedAt(new DateTime($created));

        $this->subscriberRepository->save($subscriber, true);

        return $subscriber;
    }

    public function delete(Subscriber $subscriber): void
    {
        $this->subscriberRepository->remove($subscriber, true);
    }

    public function save(Subscriber $subscriber): void
    {
        $this->subscriberRepository->save($subscriber->setUpdatedAt(new DateTime()), true);
    }

    /**
     * @return Subscriber[]
     */
    public function getInactiveSubscribersBasedOnModifier(): array
    {
        $modifier = (new DateTime())->modify(Job::DELETE_INACTIVE_SUBSCRIBER_MODIFIER)->format('Y-m-d H:i:s');

        return $this->subscriberRepository->findInactiveSubscribersBasedOnModifier($modifier);
    }

    public function import(UserInterface|User $user, string $name, string $email, bool $subscribed): void
    {
        $name = empty($name) ? $this->getNameFromEmailAddress($email) : $name;

        $entity = new Subscriber();
        $entity
            ->setUser($user)
            ->setName($name)
            ->setEmail($email)
            ->setIsSubscribed($subscribed)
            ->setToken($this->getRandomToken(32));

        $this->save($entity);
    }

    /**
     * @return Subscriber[]
     */
    public function search(string $keyword): array
    {
        return $this->subscriberRepository->search($keyword);
    }
}