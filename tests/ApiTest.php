<?php

class ApiTest extends TestCase {

    const API_ROUTE_ALL = '/api/1.0/all';

    /**
     * Test that the api list doesn't fail.
     *
     * @return void
     */
    public function testRouteAll()
    {
        $this
            ->get(static::API_ROUTE_ALL)
            ->seeJson([
                'status' => 200,
            ]);
    }

    public function testRouteAllHasItems()
    {
        $response = $this->call('GET', static::API_ROUTE_ALL);
        $content  = json_decode($response->content());

        $this->assertNotEmpty($content->data->twitter);
    }
}
