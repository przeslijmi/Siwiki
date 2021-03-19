<?php declare(strict_types=1);

namespace Przeslijmi\Siwiki\Exceptions;

use Exception;
use Throwable;

/**
 * Asked to include docs generation but `_docs.json` is not present in source uri.
 */
class DocsJsonDonoexException extends Exception
{

    /**
     * Construct.
     */
    public function __construct()
    {

        parent::__construct(
            'Asked to include docs generation but `_docs.json` is not present in source uri.',
        );
    }
}
