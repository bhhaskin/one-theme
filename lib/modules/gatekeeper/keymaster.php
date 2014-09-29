<?php


session_start();


 require_once("GateKeeper.php");

 $gatekeeper = new GateKeeperCaptcha();
 $gatekeeper->create();
 $gatekeeper->render();
