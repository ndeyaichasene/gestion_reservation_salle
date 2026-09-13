<?php

declare(strict_types=1);

namespace App\View;

use App\Session\SessionManager;

final class Renderer
{
    private string $templateDir;
    private SessionManager $session_manager;

    public function __construct(SessionManager $session_manager,?string $templateDir = null)
    {
        $this->session_manager = $session_manager ;
        $this->templateDir = $templateDir ?? dirname(__DIR__, 2) . '/templates';

    }

    public function renderView(string $template, array $data = [], string $layout = 'layout/base'): string {
        $templatePath = $this->templateDir . '/' . ltrim($template, '/') . '.php';

        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Vue introuvable : {$templatePath}");
        }

        $viewData = $data;
        extract($data, EXTR_SKIP);

        ob_start();

        require $templatePath;

        $content = ob_get_clean();

        if ($layout === '') {
            return (string) $content;
        }

        $layoutPath = $this->templateDir . '/' . ltrim($layout, '/') . '.php';

        if (!file_exists($layoutPath)) {
            throw new \RuntimeException("Layout introuvable : {$layoutPath}");
        }

        $flashSuccess = $this->session_manager->getSession('flash_success');
        $flashError = $this->session_manager->getSession('flash_error');
        
        $this->session_manager->removeSession('flash_success');
        $this->session_manager->removeSession('flash_error');

      
        $viewData = array_merge($data, [
            'contenu'      => $content,
            'flashSuccess' => $flashSuccess,
            'flashError'   => $flashError,
        ]);
        extract($viewData, EXTR_SKIP);

        ob_start();

        require $layoutPath;

        return (string) ob_get_clean();
    }

    public function render(string $template, array $data = [], string $layout = 'layout/base'): string {
        return $this->renderView($template, $data, $layout);
    }

    public static function e(?string $value): string
    {
        return htmlspecialchars( (string) $value, ENT_QUOTES,'UTF-8');
    }

    public function redirect(string $url, ?string $success = null, ?string $error = null): never
    {
        if ($success !== null) {

            $this->session_manager->setSession('flash_success', $success);
        }

        if ($error !== null) {

            $this->session_manager->setSession('flash_error', $error);
        }

        header("Location: {$url}");
        exit;
    }
}