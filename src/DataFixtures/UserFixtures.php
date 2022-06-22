<?php

namespace App\DataFixtures;

use App\Entity\PayRate;
use App\Entity\Shift;
use App\Entity\Timesheet;
use App\Entity\User;
use App\Entity\WorkRegistry;
use App\Entity\WorkStatus;
use App\Repository\DepartmentRepository;
use App\Repository\PositionRepository;
use App\Service\DateTimeService;
use App\Service\ShiftService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private const USER_ROLES = [
        'Kierownik' => 'ROLE_KIEROWNIK',
        'Księgowość' => 'ROLE_KSIEGOWOSC',
        'Użytkownik' => 'ROLE_USER'
    ];

    /**
     * @var Factory
     */
    protected $faker;
    /**
     * @var DepartmentRepository
     */
    private $departmentRepository;
    /**
     * @var PositionRepository
     */
    private $positionRepository;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var ShiftService
     */
    private $shiftService;

    public function __construct(
        DepartmentRepository $departmentRepository,
        PositionRepository $positionRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        ShiftService $shiftService
    ) {
        $this->departmentRepository = $departmentRepository;
        $this->positionRepository = $positionRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->shiftService = $shiftService;
    }

    public function load(ObjectManager $manager)
    {
        $date = new \DateTime('now');
        $datemonth = new \DateTime('-1 month');
        $date2months = new \DateTime('-2 month');
        $date->setTime(0, 0, 0);
        $dateWithFirstDayOfActualMonth = DateTimeService::getDateWithFirstDayOfMonth($date);
        $dateWithLastDayOfActualMonth = DateTimeService::getDateWithLastDayOfMonth($date);
        $dateWithFirstDayOfOneMonth = DateTimeService::getDateWithFirstDayOfMonth($datemonth);
        $dateWithLastDayOfOneMonth = DateTimeService::getDateWithLastDayOfMonth($datemonth);
        $dateWithFirstDayOfTwoMonth = DateTimeService::getDateWithFirstDayOfMonth($date2months);
        $dateWithLastDayOfTwoMonth = DateTimeService::getDateWithLastDayOfMonth($date2months);

        $users = $this->getManyUsers(7, $dateWithFirstDayOfTwoMonth);

        $user = new User();
        $user->setFirstName('Admin');
        $user->setLastName('Admiński');
        $user->setEmail('admin@admin.com');
        $user->setNote('Administrator');
        $user->setPostalCode('10-277');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPlainPassword('hasłoadmin');
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        $user->setCreatedAt(new \DateTime('now'));
        $user->setUpdatedAt(new \DateTime('now'));
        $user->setStreet('Słowackiego 15/3');
        $user->setTown('Olsztyn');
        $user->setTelephone('515147175');
        $user->setDepartment($this->departmentRepository->findOneBy(['name' => 'IT']));
        $user->setPosition($this->positionRepository->findOneBy(['name' => 'Administrator']));


        $payRate = new PayRate();
        $payRate->setRatePerHour(15);
        $payRate->setOvertimeRate(30);
        $payRate->setObtainFrom($dateWithFirstDayOfTwoMonth);
        $user->addPayRate($payRate);

        $user2 = new User();
        $user2->setFirstName('Jarosław');
        $user2->setLastName('Zapracowany');
        $user2->setEmail('pracownik@pracownik.com');
        $user2->setNote('Pracownik');
        $user2->setPostalCode('10-277');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPlainPassword('hasłopracownik');
        $user2->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        $user2->setCreatedAt(new \DateTime('now'));
        $user2->setUpdatedAt(new \DateTime('now'));
        $user2->setStreet('Zamenhofa 2/12');
        $user2->setTown('Olsztyn');
        $user2->setTelephone('515147175');
        $user2->setDepartment($this->departmentRepository->findOneBy(['name' => 'IT']));
        $user2->setPosition($this->positionRepository->findOneBy(['name' => 'Programista']));


        $payRate = new PayRate();
        $payRate->setRatePerHour(30);
        $payRate->setOvertimeRate(50);
        $payRate->setObtainFrom($dateWithFirstDayOfTwoMonth);
        $user2->addPayRate($payRate);

        $user3 = new User();
        $user3->setFirstName('Andrzej');
        $user3->setLastName('ZapracowanyBardziej');
        $user3->setEmail('pracownik2@pracownik.com');
        $user3->setNote('Pracownik');
        $user3->setPostalCode('10-277');
        $user3->setRoles(['ROLE_USER']);
        $user3->setPlainPassword('hasłopracownik');
        $user3->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        $user3->setCreatedAt(new \DateTime('now'));
        $user3->setUpdatedAt(new \DateTime('now'));
        $user3->setStreet('Zamenhofa 2/15');
        $user3->setTown('Olsztyn');
        $user3->setTelephone('515147175');
        $user3->setDepartment($this->departmentRepository->findOneBy(['name' => 'IT']));
        $user3->setPosition($this->positionRepository->findOneBy(['name' => 'Programista']));


        $payRate = new PayRate();
        $payRate->setRatePerHour(30);
        $payRate->setOvertimeRate(50);
        $payRate->setObtainFrom($dateWithFirstDayOfTwoMonth);
        $user3->addPayRate($payRate);

        $users[] = $user;
        $users[] = $user2;
        $users[] = $user3;
        foreach ($users as $user) {
            $manager->persist($user);
        }

        $manager->flush();

        $timesheets = array();
        foreach ($users as $user) {
            $timesheetOneMonth = Timesheet::createRandomTimesheet($dateWithFirstDayOfOneMonth,
                $dateWithLastDayOfOneMonth);
            $timesheetOneMonth["userId"] = $user->getId();
            $timesheetTwoMonth = Timesheet::createRandomTimesheet($dateWithFirstDayOfTwoMonth,
                $dateWithLastDayOfTwoMonth);
            $timesheetTwoMonth["userId"] = $user->getId();
            $timesheet = Timesheet::createRandomTimesheet($dateWithFirstDayOfActualMonth,
                $dateWithLastDayOfActualMonth);
            $timesheet["userId"] = $user->getId();
            $timesheets[] = $timesheetOneMonth;
            $timesheets[] = $timesheetTwoMonth;
            $timesheets[] = $timesheet;
        }

        $shifts = $this->shiftService->createShiftFromTimesheets($timesheets);
        foreach ($shifts as $shift) {
            $manager->persist($shift);
        }
        $manager->flush();


        foreach ($shifts as $shift) {
            if ($date >= $shift->getDate()) {
                $statusEnter = $this->generateWorkStatus($shift, WorkRegistry::ENTER_WORK);
                $shift->addWorkStatus($statusEnter);
                $statusExit = $this->generateWorkStatus($shift, WorkRegistry::EXIT_WORK);
                $shift->addWorkStatus($statusExit);
            }
        }
        $manager->flush();

    }

    public function getManyUsers(int $usersQuantity, $dateOfPayRate)
    {
        $users = array();
        $this->faker = Factory::create('pl_PL');
        $departments = $this->departmentRepository->findAll();
        $positions = $this->positionRepository->findAll();

        for ($i = 0; $i < $usersQuantity; $i++) {
            $user = new User();
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setStreet($this->faker->streetAddress);
            $user->setNote($this->faker->sentence(6));
            $user->setTown($this->faker->city);
            $user->setPostalCode($this->faker->postcode);
            $user->setEmail($this->faker->email);
            $user->setTelephone($this->faker->phoneNumber);
            $user->setPosition($this->faker->randomElement($positions, 1));
            $user->setDepartment($this->faker->randomElement($departments, 1));
            $payrate = new PayRate();
            $payrate->setObtainFrom($dateOfPayRate);
            $payrate->setRatePerHour($this->faker->numberBetween(10, 20));
            $payrate->setOvertimeRate($this->faker->numberBetween(20, 40));
            $user->addPayRate($payrate);
            $user->setPlainPassword('qwerty');
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
            $user->setCreatedAt(new \DateTime('now'));
            $user->setUpdatedAt(new \DateTime('now'));
            $user->setRoles(explode(',', $this->faker->randomElement(self::USER_ROLES, 1)));

            $users[] = $user;
        }
        return $users;
    }

    /**
     * @var Shift $shift
     */
    public function generateWorkStatus($shift, $status)
    {
        $this->faker = Factory::create();

        $startTime = $shift->getStartTime();
        $endTime = $shift->getEndTime();
        $workDate = $shift->getDate();
        $year = $workDate->format('Y');
        $month = $workDate->format('m');
        $day = $workDate->format('d');

        if ($status === WorkRegistry::ENTER_WORK) {
            $start = (int)$startTime->format('H');
            $start -= 1;
            $end = $start + 2;
            $startDate = new \DateTime();
            $startDate->setDate($year, $month, $day)->setTime($start, 0, 0);
            $endDate = new \DateTime();
            $endDate->setDate($year, $month, $day)->setTime($end, 0, 0);
            $date = $this->faker->dateTimeBetween($startDate, $endDate);
            $workStatus = new WorkStatus($shift->getUser(), $shift, WorkRegistry::ENTER_WORK, $date, 'RCP');
            return $workStatus;
        } else {
            $start = (int)$endTime->format('H');
            $start -= 1;
            $end = $start + 2;
            $startDate = new \DateTime();
                $endDay = $day;
            if($startTime < $endTime){
                $endDay ++;
            }
            $startDate->setDate($year, $month, $endDay)->setTime($start, 0, 0);
            $endDate = new \DateTime();
            $endDate->setDate($year, $month, $endDay)->setTime($end, 0, 0);
            $date = $this->faker->dateTimeBetween($startDate, $endDate);
            $workStatus = new WorkStatus($shift->getUser(), $shift, WorkRegistry::EXIT_WORK, $date, 'RCP');
            return $workStatus;
        }

    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return array(DepartmentFixture::class, PositionFixture::class);
    }
}
