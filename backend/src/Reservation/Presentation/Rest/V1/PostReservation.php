<?php
declare(strict_types=1);

namespace Fynkus\Reservation\Presentation\Rest\V1;

use Fynkus\Reservation\Application\Service\PostReservationService;
use Fynkus\Reservation\Domain\Exception\ReservationDuplicateException;
use Fynkus\Reservation\Domain\Exception\SpacesNotFoundException;
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


#[Route('/reservation', name: 'post_reservation', methods: ['POST'])]
class PostReservation extends AbstractController
{
    public function __construct(
        private readonly PostReservationService $postReservtionService,
        private readonly LoggerInterface $logger,
    ) { 
    }

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $this->logger->info('Start New Reservation');

            
            $data = json_decode($request->getContent(), true);

            
            $spaceUuid = new SpaceUuid($data['spaceUuid']);
            $date = new DateVo($data['date']);

            $slots = array_map(
                fn ($slot) => new SlotVo(
                    new StatusVo($slot['status']),
                    new HourVo($slot['hour'])
                ),
                $data['slots']
            );

            $reservationVo = ReservationVo::create(
                $spaceUuid,
                $date,
                new SlotsVo(...$slots)
            );



            $uuids = $this->postReservtionService->__invoke($reservationVo);
            $data = array_map(
                fn (string $uuid) => ['uuid' => $uuid],
                $uuids
            );
            return $this->json($data, Response::HTTP_CREATED);
        } catch (InvalidArgumentException $e) {
            $this->logger->error('Not Found :( -> ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (ReservationDuplicateException $e) {
            $this->logger->error('Not Found :( -> ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_CONFLICT);
        } catch (SpacesNotFoundException $e) {
            $this->logger->error('Not Found :( -> ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_CONFLICT);
        } catch (HttpException $e) {
            $this->logger->error('Error in get all spaces :( -> ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
    }
}

