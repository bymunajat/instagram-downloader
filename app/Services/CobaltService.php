<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CobaltService
{
    protected $client;

    public function __construct()
    {
        // ambil konfigurasi dari file config/cobalt.php
        $config = include __DIR__ . '/../Config/cobalt.phpc';

        $this->client = new Client([
            'base_uri' => $config['api_url'], // base URL Cobalt lokal
            'timeout'  => $config['timeout'],
        ]);
    }

    /**
     * Download Instagram media
     *
     * @param string $instagramUrl
     * @return array
     */
    
    public function downloadInstagram(string $instagramUrl): array
    {
        try {
            $response = $this->client->get('instagram', [
                'json' => ['url' => $instagramUrl]
            ]);

            $data = json_decode($response->getBody(), true);

            if (!isset($data['instagram']['media'])) {
                return ['error' => 'No media found'];
            }

            $mediaItems = [];
            foreach ($data['instagram']['media'] as $index => $media) {
                $mediaItems[] = [
                    'url' => $media['url'],
                    'type' => $media['type'] // video / image
                ];
            }

            return $mediaItems;

        } catch (RequestException $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
