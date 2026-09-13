<?php

declare(strict_types=1);

namespace App\Security;
use App\Session\SessionManager;

final class CsrfManager
{
    public const CLETOKEN = 32;
    public function __construct(private SessionManager $session_manager){}

    public function generateToken():string{
        if ( $this->session_manager->hasSession('csrf_token')) {
           return $this->session_manager->getSession('csrf_token');
           
        }
            $token = bin2hex(random_bytes(self::CLETOKEN));
            $this->session_manager->setSession('csrf_token',$token);
            return $token;
    }

    public function validateToken(string $token): bool{
        if (! $this->session_manager->hasSession('csrf_token')) {
            return false;

        }
            $sessionToken = $this->session_manager->getSession('csrf_token');
            return hash_equals($sessionToken,$token);

    }
}