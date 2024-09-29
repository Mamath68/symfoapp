<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Services\YugiohProdeck;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route('/cards')]
class YugiohController extends AbstractController
{

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route(path: '/', name: 'app_cards', methods: ['GET'])]
    public function index(YugiohProdeck $apiService): Response
    {
        $cards = $apiService->getCards(['num' => 20, 'offset' => 0]);

        return $this->render('yugioh/index.html.twig', [
            'cards' => $cards['data'] ?? [],
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/{id}', name: 'app_card_detail', methods: ['GET'])]
    public function show(int $id, YugiohProdeck $apiService): Response
    {
        $card = $apiService->getCardById($id);

        if (!$card) {
            throw $this->createNotFoundException('La carte demandée n\'existe pas.');
        }

        return $this->render('yugioh/show/show.html.twig', [
            'card' => $card,
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/archetype/{archetype}', name: 'app_archetype_cards', requirements: ['archetype' => '.+'], methods: ['GET'])]
    public function showArchetype(string $archetype, YugiohProdeck $apiService): Response
    {
        $archetype = str_replace('-', '/', $archetype);
        $cards = $apiService->getCardsByArchetype($archetype);

        return $this->render('yugioh/show/show_archetype.html.twig', [
            'archetype' => $archetype,
            'cards' => $cards,
        ]);
    }
}
