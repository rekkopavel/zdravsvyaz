<?php
declare(strict_types=1);

namespace App\Enum;

enum PasteAccessType: int
{
    case PUBLIC = 1;
    case UNLISTED = 0;
}
