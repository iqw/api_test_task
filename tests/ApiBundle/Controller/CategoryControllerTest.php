<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testGetList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/category', [], [], ['HTTP_ACCEPT' => 'application/json', 'HTTP_CONTENT_TYPE' => 'application/json']);

        $response = $client->getResponse();
        $contentType = $response->headers->get('content-type', '');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $contentType);
    }
}
