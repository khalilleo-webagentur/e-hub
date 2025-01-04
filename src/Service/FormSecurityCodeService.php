<?php

declare(strict_types=1);

namespace App\Service;

use Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class FormSecurityCodeService
{
    private const FIRST_NUM = '_1';
    private const SECOND_NUM = '_2';

    public function __construct(
        private readonly SessionInterface $session,
        private string                    $sessionName
    ) {
    }

    /**
     * @throws Exception
     */
    public function getSecurityCode(): array
    {
        $this->cleanUp();

        $this->session->set($this->sessionName . self::FIRST_NUM, random_int(1, 999));
        $this->session->set($this->sessionName . self::SECOND_NUM, random_int(1, 9));

        return [
            $this->session->get($this->sessionName . self::FIRST_NUM),
            $this->session->get($this->sessionName . self::SECOND_NUM),
        ];
    }

    public function isSecurityCodeCorrect(string $sumInput): bool
    {
        $numberOne = (int)$this->session->get($this->sessionName . self::FIRST_NUM);
        $numberTwo = (int)$this->session->get($this->sessionName . self::SECOND_NUM);

        return $numberOne + $numberTwo === (int)$sumInput;
    }

    public function cleanUp(): void
    {
        $this->session->remove($this->sessionName . self::FIRST_NUM);
        $this->session->remove($this->sessionName . self::SECOND_NUM);
    }
}
