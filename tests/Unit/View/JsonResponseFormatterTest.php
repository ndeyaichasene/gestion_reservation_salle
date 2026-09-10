<?php

declare(strict_types=1);

namespace Tests\Unit\View;

use App\View\JsonResponseFormatter;
use App\View\Response;
use App\View\ResponseFormatterInterface;
use PHPUnit\Framework\TestCase;

final class JsonResponseFormatterTest extends TestCase
{
    private JsonResponseFormatter $formatter;

    protected function setUp(): void
    {
        $this->formatter = new JsonResponseFormatter();
    }

    public function testImplementeInterface(): void
    {
        $this->assertInstanceOf(ResponseFormatterInterface::class, $this->formatter);
    }

    public function testFormatRetourneJsonValide(): void
    {
        $donnees = [
            'title'  => 'Test JSON',
            'status' => 'ok',
            'count'  => 42,
        ];
        $response = new Response($donnees);

        $json = $this->formatter->format($response);

        $this->assertJson($json);
        $decode = json_decode($json, true);
        $this->assertArrayHasKey('data', $decode);
        $this->assertSame($donnees, $decode['data']);
    }

    public function testFormatAvecTableauVide(): void
    {
        $response = new Response([]);

        $json = $this->formatter->format($response);

        $this->assertJson($json);
        $decode = json_decode($json, true);
        $this->assertSame(['data' => []], $decode);
    }
}
