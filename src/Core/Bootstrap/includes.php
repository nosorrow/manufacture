<?php

foreach (glob(APPLICATION_DIR . 'Functions/*.php') as $functions) {

    include_once $functions;
}
foreach (glob(SYSTEM_DIR . 'Helpers/*.php') as $helpers) {

    include_once $helpers;
}