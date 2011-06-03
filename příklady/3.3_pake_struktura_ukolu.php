<?php

pake_desc('Description of A task');
pake_task('A');

pake_desc('Description of B task');
pake_task('B', 'A');

function run_a() {}
function run_b() {}