<?php

//            ---------------------------------------------------
//                              Omega Framework                                
//            ---------------------------------------------------
//                  Copyright (C) <2020>  <Entynetproject>       
//
//        This program is free software: you can redistribute it and/or modify
//        it under the terms of the GNU General Public License as published by
//        the Free Software Foundation, either version 3 of the License, or
//        any later version.
//
//        This program is distributed in the hope that it will be useful,
//        but WITHOUT ANY WARRANTY; without even the implied warranty of
//        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
//        GNU General Public License for more details.
//
//        You should have received a copy of the GNU General Public License
//        along with this program.  If not, see <http://www.gnu.org/licenses/>.

!import(mysqli_compat)

// Establish connection
$host = $OMEGA["HOST"];
$user = $OMEGA["USER"];
$pass = $OMEGA["PASS"];

$conn = @mysqli_connect($host, $user, $pass);
if (!$conn)
    return error("ERROR: %s: %s", @mysqli_connect_errno(), @mysqli_connect_error());

//@mysql_close($connect);
// NOTE:
// commented due to a bug in rare servers (bug found in iis6.0/php5.2.11)

return "OK";

?>
