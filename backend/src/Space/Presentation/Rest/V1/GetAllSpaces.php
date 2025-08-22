<?php
declare(strict_types=1);

namespace Fynkus\Space\Presentation\Rest\V1;

use Fynkus\Space\Application\Service\GetAllSpacesService;
use Fynkus\Space\Application\Response\SpaceResponse;
use Fynkus\Space\Domain\Exception\ListSpacesEmptyException;
use Psr\Log\LoggerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

use function Lambdish\Phunctional\map;

#[Route('/space', name: 'get_all_space', methods: ['GET'])]
class GetAllSpaces extends AbstractController
{
    public function __construct(
        private readonly GetAllSpacesService $getAllSpacesService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        try {
            $this->logger->info('Start get All Spaces.');

            $spaces = $this->getAllSpacesService->__invoke();

            $data = array_map(
                fn (SpaceResponse $space) => [
                    'uuid' => $space->uuid(),
                    'name' => $space->name(),
                ],
                $spaces->gets()
            );

            return $this->json($data, Response::HTTP_OK);
        } catch (ListSpacesEmptyException $e) {
            $this->logger->error('Not Found :( -> ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (HttpException $e) {
            $this->logger->error('Error in get all spaces :( -> ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
    }
}

