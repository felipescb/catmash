<?php

function mix_except_in_tests($path, $manifestDirectory = '')
{
    if (app()->runningUnitTests()) {
        return '';
    }

    return mix($path, $manifestDirectory);
}
