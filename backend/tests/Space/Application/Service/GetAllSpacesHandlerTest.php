<?php
declare(strict_types=1);

namespace Fynkus\Tests\Space\Application\Service;

use Faker\Factory as FakerFactory;
use Fynkus\Space\Application\Response\SpaceResponse;
use Fynkus\Space\Application\Response\SpaceResponses;
use Fynkus\Space\Domain\Exception\ListSpacesEmptyException;
use Fynkus\Space\Domain\Service\ISpaceService;
use PHPUnit\Framework\TestCase;

class GetAllSpacesHandlerTest extends TestCase
{

    private $faker;
    private ISpaceService $spaceService;

    public function setUp(): void
    {
        $this->faker = FakerFactory::create();

        $this->spaceService = $this->createMock(ISpaceService::class);
    }

    public function test_it_returns_all_spaces(): void
    {
        $uuid = $this->faker->uuid();
        $name = 'Pista de Pádel';

        $spaceResponses = new SpaceResponses(
            new SpaceResponse($uuid, $name)
        );

        $this->spaceService
            ->expects($this->once())
            ->method('getAll')
            ->willReturn($spaceResponses);


        $result = $this->spaceService->getAll();

        $this->assertInstanceOf(SpaceResponses::class, $result);
        $this->assertCount(1, $result->gets());

        $space = $result->gets()[0];
        $this->assertEquals($uuid, $space->uuid());
        $this->assertEquals($name, $space->name());
    }

    public function test_it_throws_if_no_spaces(): void
    {
        $this->spaceService
            ->expects($this->once())
            ->method('getAll')
            ->willThrowException(new ListSpacesEmptyException());

        $this->expectException(ListSpacesEmptyException::class);

        $this->spaceService->getAll();
    }    


}