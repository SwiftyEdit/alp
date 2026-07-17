<?php

$modinc = match (true) {
    str_ends_with($query, 'read/') => 'reader',
    default => ''
};

if($modinc != '') {
    include __DIR__.'/'.$modinc.'.php';
}