<?php

function route_class()
{
   return str_replace('.', '_', Route::currentRouteName() );
}
