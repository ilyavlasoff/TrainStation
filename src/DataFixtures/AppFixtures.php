<?php

namespace App\DataFixtures;

use App\Entity\Benefits;
use App\Entity\Bonuses;
use App\Entity\Monitoring;
use App\Entity\Station;
use App\Entity\Ticket;
use App\Entity\TicketStatus;
use App\Entity\Train;
use App\Entity\User;
use App\Entity\Voyage;
use App\Entity\Wagon;
use App\Entity\WagonType;
use App\Entity\WayThrough;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(EncoderFactoryInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $count = [
            'users' => 10,
            'trains' => 5,
            'wagons' => 50,
            'voyages' => 30,
            'stations' => 8,
            'tickets' => 300,
            'waysThroughStations' => 200
        ];
        $cities = ['Москва', 'СПБ', 'Воронеж', 'Тамбов', 'Липецк', 'Рязань', 'Калининград', 'Владивосток'];
        $names = ['Анатолий', 'Сергей', 'Борис', 'Николай', 'Владимир', 'Алексей', 'Илья'];
        $patronymics = ['Анатольевич', 'Сергеевич', 'Борисович', 'Николаевич', 'Владимирович', 'Алексеевич', 'Илья'];
        $surnames = ['Иванов','Петров','Степанов','Моисеев', 'Прокофьев', 'Алексеев', 'Борисов'];

        $users = [];
        for($i =0; $i != $count['users']; ++$i) {
            /** @var User $user */
            $user = new User();
            $user->setEmail(base64_encode(random_bytes(10)) . '@gmail.com');

            $test_password = 'test';
            $password = $this->encoder->getEncoder($user)->encodePassword($test_password, $user->getSalt());
            $user->setPassword($password);

            $user->setRoles(['ROLE_USER']);

            $user->setName($names[array_rand($names)]);
            $user->setSurname($surnames[array_rand($surnames)]);
            $user->setPatronymic($patronymics[array_rand($patronymics)]);

            $user->setPassportData(base64_encode(random_bytes(5)));

            $users[] = $user;
            $manager->persist($user);
            $manager->flush();
        }

        for($i =0; $i != $count['users']; ++$i) {
            $benefits = new Benefits();
            $benefits->setType('type ' . random_int(1, 5));
            $benefits->setUser($users[$i]);
            $benefits->setValidBefore(
                \DateTime::createFromFormat('j-M-Y', '15-Feb-2009'));
            $benefits->setValidDocs('passport');

            $bonuses = new Bonuses();
            $bonuses->setQuantity(random_int(0, 2000));
            $bonuses->setUser($users[$i]);
            $bonuses->setAddedDate(\DateTime::createFromFormat('j-M-Y', '15-Feb-2009'));
            $manager->persist($benefits);
            $manager->persist($bonuses);
            $manager->flush();
        }

        $trains = [];
        for($i=0; $i!= $count['trains']; ++$i) {
            $train = new Train();
            $train->setRoute("Train demo route N $i");
            $train->setTrainType("type " . random_int(1, 5));
            $trains[] = $train;

            $monitoring = new Monitoring();
            $monitoring->setTrain($train);
            $monitoring->setLocation($cities[array_rand($cities)]);
            $monitoring->setTime(\DateTime::createFromFormat('j-M-Y', '15-Feb-2009'));
            $manager->persist($train);
            $manager->flush();
        }

        $wagonTypeNames = [
            'economic' => 1200,
            'standard' => 1500,
            'comfort' => 2000,
            'premium' => 3000,
            'lux' => 5000
        ];
        $wagonTypes = [];
        foreach ($wagonTypeNames as $typeKey => $typeName) {
            $wagonType = new WagonType();
            $wagonType->setTypeDescription($typeKey);
            $wagonType->setPrice($typeName);
            $manager->persist($wagonType);
            $manager->flush();
            $wagonTypes[] = $wagonType;
        }

        $wagons = [];
        for($i=0; $i!= $count['wagons']; ++$i) {
            $wagon = new Wagon();
            $wagon->setTrain($trains[array_rand($trains)]);
            $wagon->setPlacesCount(random_int(30, 200));
            $wagon->setType($wagonTypes[array_rand($wagonTypes)]);
            $wagons[] = $wagon;
            $manager->persist($wagon);
            $manager->flush();
        }

        $voyages = [];
        for ($i=0; $i!= $count['voyages']; ++$i) {
            $voyage = new Voyage();
            $voyage->setTrain($trains[array_rand($trains)]);
            $voyage->setName("Voyage demo name N$i");
            $voyage->setDepartmentDate(
                \DateTime::createFromFormat('j-M-Y', '15-Feb-2009'));

            $voyages[] = $voyage;
            $manager->persist($voyage);
            $manager->flush();
        }

        $stations = [];
        foreach ($cities as $city) {
            $station = new Station();
            $station->setAddress($city);
            $station->setPhone(array_rand(str_split('1234567890')));
            $stations[] = $station;
            $manager->persist($station);
            $manager->flush();
        }

        $ticketStatusDescriptions = [
            'Created',
            'Active',
            'Cancelled',
            'Used'
        ];
        $ticketStatuses = [];
        foreach ($ticketStatusDescriptions as $ticketStatusDescription) {
            $ticketStatus = new TicketStatus();
            $ticketStatus->setStatusDescription($ticketStatusDescription);
            $ticketStatuses[] = $ticketStatus;
            $manager->persist($ticketStatus);
            $manager->flush();
        }

        $tickets = [];
        for($i=0; $i!=$count['tickets']; ++$i) {
            $ticket = new Ticket();
            $ticket->setUser($users[array_rand($users)]);
            $ticket->setDestinationStation($stations[array_rand($stations)]);
            $ticket->setSourceStation($stations[array_rand($stations)]);
            $ticket->setPlace(random_int(1, 300));
            $ticket->setPrice(random_int(1000, 8000));
            $ticket->setStatus($ticketStatuses[array_rand($ticketStatuses)]);
            /** @var Voyage $ticketVoyage */
            $ticketVoyage = $voyages[array_rand($voyages)];
            /** @var Train $voyageTrain */
            $voyageTrain = $ticketVoyage->getTrain();
            /** @var Wagon[] $availableWagons */
            $availableWagons = $voyageTrain->getWagons();
            $ticketWagon = $wagons[array_rand($wagons)];
            $ticket->setVoyage($ticketVoyage);
            $ticket->setWagon($ticketWagon);
            $ticket->setRouteLength(floatval(random_int(10, 5000)));
            $ticket->setPriceForKm($ticket->getPrice()/$ticket->getRouteLength());

            $tickets[] = $ticket;
            $manager->persist($ticket);
            $manager->flush();
        }

        for($i=0; $i!=$count['waysThroughStations']; ++$i) {
            $station = $stations[array_rand($stations)];
            $train = $trains[array_rand($trains)];
            $wayThrough = new WayThrough();
            $wayThrough->setTrain($train);
            $wayThrough->setStation($station);
            $manager->persist($wayThrough);
            $manager->flush();
        }
    }
}
