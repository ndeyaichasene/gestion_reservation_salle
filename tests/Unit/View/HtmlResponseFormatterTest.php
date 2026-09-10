<?php

declare(strict_types=1);

namespace Tests\Unit\View;

use App\View\HtmlResponseFormatter;
use App\View\Renderer;
use App\View\Response;
use App\View\ResponseFormatterInterface;
use PHPUnit\Framework\TestCase;

final class HtmlResponseFormatterTest extends TestCase
{
    private HtmlResponseFormatter $formatter;

    protected function setUp(): void
    {
        $renderer = new Renderer();
        $this->formatter = new HtmlResponseFormatter($renderer);
    }

    public function testImplementeInterface(): void
    {
        $this->assertInstanceOf(ResponseFormatterInterface::class, $this->formatter);
    }

    public function testFormatAvecVueNullRetourneChaineVide(): void
    {
        $response = new Response(['message' => 'aucun rendu'], null);

        $resultat = $this->formatter->format($response);

        $this->assertSame('', $resultat);
    }

    public function testFormatAvecVueValideRetourneHtml(): void
    {
        $response = new Response([
            'title' => 'Test 404',
        ], 'error/404');

        $resultat = $this->formatter->format($response);

        $this->assertStringContainsString('404', $resultat);
        $this->assertStringContainsString('Page introuvable', $resultat);
    }
}
