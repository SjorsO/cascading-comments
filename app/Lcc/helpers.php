<?php

function is_production(): bool
{
    return app()->environment('production');
}
