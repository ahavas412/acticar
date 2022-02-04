<?php

namespace App\Client;

use App\Entity\Car;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PiecesAutoClient
{
    const API_URL = "https://www.piecesauto.com/";
    const URI_GET_API_CAR_ID = "homepage/numberplate";
    const URI_GET_API_CAR_LINK = "common/seekCar";

    private HttpClientInterface $client;

    public function __construct()
    {
        $this->client = HttpClient::create(['verify_peer' => false, 'verify_host' => false]);
    }

    private function getClient(): HttpClientInterface
    {
        return $this->client;
    }

    public function getCarLink(Car $car): ?string
    {
        $cache = new FilesystemAdapter();
        $cacheName = "car_link_" . $car->getId();

        return $cache->get($cacheName, function (ItemInterface $item) use ($car) {
            $item->expiresAfter(3600); // Cache de 1 heure

            $apiCarId = $this->getApiCarId($car->getLicensePlate());
            $apiCarLink = $this->getApiCarLink($apiCarId);

            $carLink = self::API_URL . $apiCarLink;

            if (!$this->isValidCarLink($carLink)) {
                return null;
            }

            return $carLink;
        });
    }

    private function getApiCarId(string $licensePlate): string
    {
        $url = self::API_URL . self::URI_GET_API_CAR_ID . "?value=" . $licensePlate;
        $response = $this->getClient()->request('GET', $url);

        if ($response->getStatusCode() != 200) {
            throw new \Exception("Error while calling CAR_ID");
        }

        $data = $response->toArray();
        if (in_array('carId', $data)) {
            throw new \Exception("Error with CAR_ID response");
        }

        return $data['carId'];
    }

    private function getApiCarLink(string $apiCarId): string
    {
        $url = self::API_URL . self::URI_GET_API_CAR_LINK . "?carid=" . $apiCarId . "&language=fr";

        $response = $this->getClient()->request('GET', $url);

        if ($response->getStatusCode() != 200) {
            throw new \Exception("Error while calling CAR_LINK");
        }

        return $response->getContent();
    }

    // La requête prend un peu de temps car elle charge toute la page
    private function isValidCarLink(string $carLink): bool
    {
        $response = $this->getClient()->request('GET', $carLink);
        if ($response->getStatusCode() != 200) {
            return false;
        }

        return true;
    }
}