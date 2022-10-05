<?php

if (!function_exists('alert')) {

    /**
     * success
     * info
     * warning
     * danger
     * @param $type
     * @return int
     */
    function alert($type, $msg)
    {
        switch ($type) {
            case 'success':
                $alert = '<div class="alert alert-success" role="alert">' . $msg . '</div>';
                break;
            case 'info':
                $alert = '<div class="alert alert-info" role="alert">' . $msg . '</div>';
                break;
            case 'warning':
                $alert = '<div class="alert alert-warning" role="alert">' . $msg . '</div>';
                break;
            case 'danger':
                $alert = '<div class="alert alert-danger" role="alert">' . $msg . '</div>';
                break;

        }

        return $alert;
    }
}

if (!function_exists('message')) {

    function message()
    {
        return app(Core\Libs\Message::class);
    }
}

if (!function_exists('highlight_code')) {
    /**
     * Code Highlighter
     *
     * Colorizes code strings
     *
     * @param    string    the text string
     * @return    string
     */
    function highlight_code($str)
    {
        /* The highlight string function encodes and highlights
         * brackets so we need them to start raw.
         *
         * Also replace any existing PHP tags to temporary markers
         * so they don't accidentally break the string out of PHP,
         * and thus, thwart the highlighting.
         */
        $str = str_replace(
            array('&lt;', '&gt;', '<?', '?>', '<%', '%>', '\\', '</script>'),
            array('<', '>', 'phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'),
            $str
        );

        // The highlight_string function requires that the text be surrounded
        // by PHP tags, which we will remove later
        $str = highlight_string('<?php ' . $str . ' ?>', TRUE);

        // Remove our artificially added PHP, and the syntax highlighting that came with it
        $str = preg_replace(
            array(
                '/<span style="color: #([A-Z0-9]+)">&lt;\?php(&nbsp;| )/i',
                '/(<span style="color: #[A-Z0-9]+">.*?)\?&gt;<\/span>\n<\/span>\n<\/code>/is',
                '/<span style="color: #[A-Z0-9]+"\><\/span>/i'
            ),
            array(
                '<span style="color: #$1">',
                "$1</span>\n</span>\n</code>",
                ''
            ),
            $str
        );

        // Replace our markers back to PHP tags.
        return str_replace(
            array('phptagopen', 'phptagclose', 'asptagopen', 'asptagclose', 'backslashtmp', 'scriptclose'),
            array('&lt;?', '?&gt;', '&lt;%', '%&gt;', '\\', '&lt;/script&gt;'),
            $str
        );
    }
}
