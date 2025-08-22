<?php
declare(strict_types=1);

namespace Fynkus\Tests\Reservation\Application\Service;

use Faker\Factory as FakerFactory;
use Fynkus\Common\Domain\Bus\Query\QueryBus;
use Fynkus\Reservation\Application\Service\GetReservationOfOneSpaceAndDateService;
use Fynkus\Reservation\Domain\Repository\IReservationRepository;
use Fynkus\Reservation\Domain\Vo\DateVo;
use Fynkus\Reservation\Domain\Vo\SpaceUuid;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class GetReservationSpaceDateTest extends TestCase
{

    private $faker;
    private GetReservationOfOneSpaceAndDateService $service;
    private IReservationRepository $reservationRepository;
    private QueryBus $queryBus;
    private LoggerInterface $logger;
    private SpaceUuid $spaceUuid;
    private DateVo $dateVo;

    protected function setUp(): void
    {
        $this->faker = FakerFactory::create();

        $this->reservationRepository = $this->createMock(IReservationRepository::class);
        $this->queryBus = $this->createMock(QueryBus::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->service = $this->getMockBuilder(GetReservationOfOneSpaceAndDateService::class)
            ->setConstructorArgs([$this->reservationRepository, $this->queryBus, $this->logger])
            ->onlyMethods(['getSpace', 'getReservationSpaceDate'])
            ->getMock();

        $this->spaceUuid = new SpaceUuid($this->faker->uuid());
        $this->dateVo = new DateVo('21/07/2025');
    }

    public function test_it_returns_reservations_for_space_and_date(): void
    {
        $uuidValue = $this->spaceUuid->value();
        $dateStr = $this->dateVo->value();
        $dummySpaceEntity = new \stdClass();

        $expectedReservations = [
            ['hour' => 9, 'status' => 'reserved'],
            ['hour' => 10, 'status' => 'reserved']
        ];

        $this->service->expects($this->once())
            ->method('getSpace')
            ->with($uuidValue)
            ->willReturn($dummySpaceEntity);

        $this->service->expects($this->once())
            ->method('getReservationSpaceDate')
            ->with($this->spaceUuid, $this->dateVo)
            ->willReturn($expectedReservations);

        $result = $this->service->getReservation($this->spaceUuid, $this->dateVo);

        $this->assertIsArray($result);
        $this->assertEquals($expectedReservations, $result);
    }


}
// php bin/phpunit tests/Reservation/Application/Service/GetReservationSpaceDateTest.php