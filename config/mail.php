<?php
/*
@copyright

Fleet Manager v6.1

Copyright (C) 2017-2022 Hyvikk Solutions <https://hyvikk.com/> All rights reserved.
Design and developed by Hyvikk Solutions <https://hyvikk.com/>

 */
// save your email config here:
return array(
     // "driver" => "YOUR_MAIL_DRIVER",
    "driver" => "smtp",

    "host" => "ns1.syrianhost.sy",
  

    "port" => 465,
  // YOUR_MAIL_PORT
    "from" => array(
      //"address" => "dimondline@peaklinkdemo.com", // FROM_EMAIL_ADDRESS
      "address" =>"peak@diamond-line.com.sy",
      "name" => "DimondLine", // FROM_USERNAME
    ),
   //"username" => "dimondline@peaklinkdemo.com",
 "username" => "peak@diamond-line.com.sy",
 
   //"password" => "Pe@kLink11aa@@",
   "password" => "peaklink1234567890",
  
    "encryption" => 'ssl', // E.G.: SSL/TLS/...
);