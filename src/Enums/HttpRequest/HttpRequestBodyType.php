<?php
/**
 * @author XJ.
 * @Date   2025/11/10
 */

namespace Fatbit\Utils\Enums\HttpRequest;

use Fatbit\Enums\Annotations\EnumCase;
use Fatbit\Enums\Interfaces\EnumCaseInterface;
use Fatbit\Enums\Traits\EnumCaseGet;

enum HttpRequestBodyType: string implements EnumCaseInterface
{
    use EnumCaseGet;

    #[EnumCase('http-body-type:json')]
    case JSON = 'json';

    #[EnumCase('http-body-type:query')]
    case QUERY = 'query';

    #[EnumCase('http-body-type:body')]
    case BODY = 'body';

    #[EnumCase('http-body-type:multipart')]
    case MULTIPART = 'multipart';

    #[EnumCase('http-body-type:form_params')]
    case FORM_PARAMS = 'form_params';


}