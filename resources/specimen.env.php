<?php declare(strict_types=1);

return [
    'PRZESLIJMI_SIWIKI_HANDLE_EXCEPTIONS' => false,
    'PRZESLIJMI_SIWIKI_COMMANDS' => [
        'md.include' => 'Przeslijmi\Siwiki\Commands\Md\IncludeMd',
        'html.addClassToTag' => 'Przeslijmi\Siwiki\Commands\Html\AddClassToTag',
        'html.include' => 'Przeslijmi\Siwiki\Commands\Html\IncludeHtml',
    ]
];
