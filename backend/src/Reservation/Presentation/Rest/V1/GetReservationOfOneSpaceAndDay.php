<?php
declare(strict_types=1);

namespace Fynkus\Reservation\Presentation\Rest\V1;

use Fynkus\Reservation\Application\Service\GetReservationOfOneSpaceAndDateService;
use Fynkus\Reservation\Application\Service\PostReservationService;
use Fynkus\Reservation\Domain\Exception\ReservationDuplicateException;
use Fynkus\Reservation\Domain\Exception\SpacesNotFoundException;
use Fynkus\Reservation\Domain\Repository\IReservationRepository;
use Fynkus\Reservation\Domain\Vo\DateVo;
use Fynkus\Reservation\Domain\Vo\HourVo;
use Fynkus\Reservation\Domain\Vo\ReservationVo;
use Fynkus\Reservation\Domain\Vo\SlotsVo;
use Fynkus\Reservation\Domain\Vo\SlotVo;
use Fynkus\Reservation\Domain\Vo\SpaceUuid;
use Fynkus\Reservation\Domain\Vo\StatusVo;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/reservation/space/{uuid}/vailability', name: 'post_reservation_space_vailability', methods: ['get'])]
class GetReservationOfOneSpaceAndDay extends AbstractController
{
    public function __construct(
        private readonly GetReservationOfOneSpaceAndDateService $reservationService,
        private readonly LoggerInterface $logger,
    ) { 
    }

    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        try {

            $this->logger->info('Start Get Reservation of One Space and Day');
            $dateString = $request->query->get('date');
            if (!$dateString) {
                return $this->json(['error' => 'Date parameter is missing'], 400);
            }
            
            $spaceUuid = new SpaceUuid($uuid);
            $date = new DateVo($dateString);



            $plannings = $this->reservationService->__invoke($spaceUuid, $date);
            $data = array_map(
                fn ($p) => [
                    'uuid' => $p->getUuid(),
                    'date' => $p->getDate()->format('Y-m-d'),
                    'space' => $p->getSpace(),
                    'Hour' => $p->getHour(),
                    'status' => $p->getStatus()

                
                ],
                $plannings
            );
            return $this->json($data, Response::HTTP_OK);

            
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Not Found :( -> ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (SpacesNotFoundException $e) {
            $this->logger->error('Not Found :( -> ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_CONFLICT);
        } catch (HttpException $e) {
            $this->logger->error('Error in get all spaces :( -> ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
    }
}

