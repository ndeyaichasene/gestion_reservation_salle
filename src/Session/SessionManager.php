<?php

declare(strict_types=1);

namespace App\Session;

final class SessionManager
{
    public function __construct()
    {
        $this->startSession();
    }

    private function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

  
    public  function getSession(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

   
    public  function setSession(string $key, mixed $value): void
    {
         $_SESSION[$key] = $value;
    }


    public  function hasSession(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public  function removeSession(string $key): void
    {
        unset($_SESSION[$key]);
    }


    public  function destroySession(): void
    {
        if (session_status() !== PHP_SESSION_NONE) {
            session_unset();
            session_destroy();
        }
    }

   
    public  function all(): array
    {
        return $_SESSION ?? [];
    }


    public  function clear(): void
    {
        $_SESSION = [];
    }
}