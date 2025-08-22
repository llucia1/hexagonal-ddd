<?php
declare(strict_types=1);

namespace Fynkus\Tests\Reservation\Application\Service;

use Faker\Factory as FakerFactory;
use Fynkus\Common\Domain\Bus\Query\QueryBus;
use Fynkus\Reservation\Application\Service\PostReservationService;
use Fynkus\Reservation\Domain\Repository\IReservationRepository;
use Fynkus\Reservation\Domain\Vo\DateVo;
use Fynkus\Reservation\Domain\Vo\HourVo;
use Fynkus\Reservation\Domain\Vo\ReservationVo;
use Fynkus\Reservation\Domain\Vo\SlotsVo;
use Fynkus\Reservation\Domain\Vo\SlotVo;
use Fynkus\Reservation\Domain\Vo\SpaceUuid;
use Fynkus\Reservation\Domain\Vo\StatusVo;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class PostReservationTest extends TestCase
{

    private $faker;
    private PostReservationService $service;
    private IReservationRepository $reservationRepository;
    private QueryBus $queryBus;
    private LoggerInterface $logger;

    private ReservationVo $reservationVo;
    private SlotsVo $slotsVo;
    private SpaceUuid $spaceUuid;
    private DateVo $dateVo;

    protected function setUp(): void
    {
        $this->faker = FakerFactory::create();

        $this->reservationRepository = $this->createMock(IReservationRepository::class);
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->service = $this->getMockBuilder(PostReservationService::class)
            ->setConstructorArgs([$this->reservationRepository, $this->queryBus, $this->logger])
            ->onlyMethods(['getSpace', 'exitsReservationByDate', 'saveAllReservation'])
            ->getMock();

        $this->spaceUuid = new SpaceUuid($this->faker->uuid());
        $this->dateVo = new DateVo('21/07/2025');
        $this->slotsVo = new SlotsVo(
            new SlotVo(new StatusVo('reserved'), new HourVo(9)),
            new SlotVo(new StatusVo('reserved'), new HourVo(10))
        );
        $this->reservationVo = new ReservationVo(
            $this->spaceUuid,
            $this->dateVo,
            $this->slotsVo
        );
    }

    public function test_it_creates_a_reservation(): void
    {
        $uuid1 = $this->faker->uuid();
        $uuid2 = $this->faker->uuid();
        $this->service->expects($this->once())
            ->method('getSpace')
            ->with($this->spaceUuid->value())
            ->willReturn(new \stdClass());

        $this->service->expects($this->once())
            ->method('exitsReservationByDate')
            ->with($this->dateVo->value());

        $this->service->expects($this->once())
            ->method('saveAllReservation')
            ->with($this->slotsVo, $this->isInstanceOf(\stdClass::class), $this->dateVo->value())
            ->willReturn([ $uuid1, $uuid2 ]);

        $result = $this->service->createReservation($this->reservationVo);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals([ $uuid1, $uuid2 ], $result);
    }


}
// php bin/phpunit tests/Reservation/Application/Service/PostReservationTest.php