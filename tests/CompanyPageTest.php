<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CompanyPageTest extends WebTestCase
{
    public function test_loadpage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $response = $client->getResponse();
        //print_r($response);

        $this->assertResponseIsSuccessful();
    }

    public function test_mailsubmit(): void
    {
        $client = static::createClient();
        $crawler = $client->request(
            'POST',
            '/',
            ['company' => [
                'email' => 'sample@hotmail.com',
                'symbol' => 'AAIT',
                'startdate' => '01-05-2023',
                'enddate' => '28-05-2023'
            ]]
        );

        $this->assertResponseIsSuccessful();
    }
}
