<?php

    class DateTimeUtils {

        public static function getDiffYear($date1, $date2) {

            $strDate1 = strtotime( $date1 );
            $strDate2 = strtotime( $date2 );            

            $diff = $strDate2 - $strDate1;

            return floor($diff / (365*60*60*24));

        }

    }