<?php

namespace App\Controller;

use App\Dto\UrlInput;
use App\Exception\UrlNotFoundException;
use App\Service\UrlService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UrlController extends AbstractController
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;
    private UrlService $urlService;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UrlService $urlService
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->urlService = $urlService;
    }

    /**
     * Redirection vers l'URL originale
     * Cette route est définie en dehors du groupe /api
     */
    #[Route('/{code}', name: 'redirect_url', methods: ['GET'], priority: 1)]
    public function redirectToOriginalUrl(string $code): Response
    {
        try {
            $originalUrl = $this->urlService->getOriginalUrl($code);
            return $this->redirect($originalUrl);
        } catch (UrlNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Création d'une URL raccourcie
     */
    #[Route('/api/urls', name: 'api_create_url', methods: ['POST'])]
    public function createUrl(Request $request): JsonResponse
    {
        // Désérialiser la requête en DTO
        /** @var UrlInput $urlInput */
        $urlInput = $this->serializer->deserialize(
            $request->getContent(),
            UrlInput::class,
            'json'
        );

        // Valider les données d'entrée
        $violations = $this->validator->validate($urlInput);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        // Créer l'URL raccourcie
        $urlOutput = $this->urlService->createShortUrl($urlInput);

        return $this->json($urlOutput, Response::HTTP_CREATED);
    }

    /**
     * Récupération des informations d'une URL raccourcie
     */
    #[Route('/api/urls/{code}', name: 'api_get_url', methods: ['GET'])]
    public function getUrl(string $code): JsonResponse
    {
        try {
            $urlOutput = $this->urlService->getUrlInfo($code);
            return $this->json($urlOutput);
        } catch (UrlNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Recherche de l'URL originale à partir d'un code
     */
    #[Route('/api/lookup', name: 'api_lookup_url', methods: ['POST'])]
    public function lookupUrl(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        if (!isset($data['code']) || empty($data['code'])) {
            return $this->json(['error' => 'Le code est requis'], Response::HTTP_BAD_REQUEST);
        }
        
        $code = $data['code'];
        
        try {
            $urlOutput = $this->urlService->getUrlInfo($code);
            return $this->json($urlOutput);
        } catch (UrlNotFoundException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}