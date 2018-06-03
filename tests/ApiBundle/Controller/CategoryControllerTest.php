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

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('success', $data);
        $this->assertArrayHasKey('data', $data);

        $data = $data['data'];

        $this->assertArrayHasKey('current_page', $data);
        $this->assertArrayHasKey('items', $data);
        $this->assertArrayHasKey('page_count', $data);
        $this->assertArrayHasKey('items_per_page', $data);
        $this->assertArrayHasKey('total_item_count', $data);

        $calculatedPage = ceil($data['total_item_count'] / $data['items_per_page']);

        $this->assertEquals($data['current_page'], $calculatedPage);
        $this->assertLessThanOrEqual($data['items_per_page'], count($data['items']));
    }
}
