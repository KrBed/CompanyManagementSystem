<?php


namespace App\Service;


class DateTimeService
{
        public static function getDaysInMonth(\DateTime $date){
            $monthDays = array();
            $month = $date->format('m');
            $year = $date->format('Y');
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);


            for ($i = 1; $i <= $daysInMonth; $i++) {
                $dateToAdd = new \DateTime();
                $fullDate = $dateToAdd->setDate($year, $month, $i)->format('d-m-Y');
                $fDate = $dateToAdd->setDate($year, $month, $i)->format('d.m.');
                $fDay = $dateToAdd->setDate($year, $month, $i)->format('D');
                $numericDay = $dateToAdd->setDate($year, $month, $i)->format('N');
                $monthDays[$fullDate] = ['day' => $fDay, 'numericDay' => $numericDay, 'monthDay' => $fDate];
            }
            return $monthDays;
        }

        public static function getDateFromDateString($date,$direction = null){
            $day = (int)substr($date, 0, 2);
            $month = (int)substr($date, 3, 2);
            $year = (int)substr($date, 6, 4);
            $date = new \DateTime();
            $date->setDate($year,$month,$day);
            if($direction !="prev" || $direction !="next"){
                return $date;
            }

                if ($direction == "prev"){
                  $date->sub(new \DateInterval('P1M'));
                }
                else{
                    $date->add(new \DateInterval('P1M'));
                }
            return $date;
        }

        public static function getDateWithFirstDayOfMonth(\DateTime $date){
            $month = $date->format('m');
            $year = $date->format('Y');
            $returnDate= new \DateTime();

            return $returnDate->setDate($year,$month,1)->setTime(0,0,0,0);
        }

        public static function getDateWithLastDayOfMonth(\DateTime $date){
            $month = $date->format('m');
            $year = $date->format('Y');
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $returnDate= new \DateTime();

            return $returnDate->setDate($year,$month,$daysInMonth)->setTime(0,0,0,0);
        }
}