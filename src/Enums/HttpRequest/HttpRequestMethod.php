<?php
/**
 * @author XJ.
 * @Date   2025/11/10
 */

namespace Fatbit\Utils\Enums\HttpRequest;

use Fatbit\Enums\Annotations\EnumCase;
use Fatbit\Enums\Interfaces\EnumCaseInterface;
use Fatbit\Enums\Traits\EnumCaseGet;

enum  HttpRequestMethod: string implements EnumCaseInterface
{
    use EnumCaseGet;

    #[EnumCase('HTTP-METHOD:GET')]
    case GET = 'GET';

    #[EnumCase('HTTP-METHOD:POST')]
    case POST = 'POST';

    #[EnumCase('HTTP-METHOD:PUT')]
    case PUT = 'PUT';

    #[EnumCase('HTTP-METHOD:DELETE')]
    case DELETE = 'DELETE';

    #[EnumCase('HTTP-METHOD:HEAD')]
    case HEAD = 'HEAD';

    #[EnumCase('HTTP-METHOD:OPTIONS')]
    case OPTIONS = 'OPTIONS';

    #[EnumCase('HTTP-METHOD:PATCH')]
    case PATCH = 'PATCH';
}