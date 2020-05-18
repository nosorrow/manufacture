<?php
use Core\Libs\Session;

/* --- SESSION HELPERS ---- */

if (!function_exists('sessionData')) {
    /**
     * get Session data from $_SESSION
     * Ako $name е масив или имаме стойност в value
     * то ще слагаме сеиия ($nama=>$value),
     *  иначе вземам сесия с име $name;
     */
    function sessionData($key = null, $data = null)
    {
        $session = app(Session::class);

        if ($key == null && $data == null){
            return $session->get_all();
        }

        if (is_array($key)) {
            return $session->store($key);

        } elseif ($key !== null && $data !== null) {
            return $session->store($key, $data);

        } else {
            return $session->getData($key);
        }

    }
}

if (!function_exists('session_has')) {

    function session_has($key){
        $session = app(Session::class);
        return $session->has($key);
    }
}


if (!function_exists('flash')) {
    /**
     * Get flash Session msg
     */
    function flash($name)
    {
        $session = app(Session::class);

        return $session->getFlash($name);
    }
}


if (!function_exists('session_push')) {
    /**
     *
     */
    function session_push($key, $value)
    {
        $session = app(Session::class);

        $session->push($key, $value);
    }
}

if (!function_exists('session_pull')) {

    /**
     *  Session pull
     * function returns and removes a key / value pai
     */
    function session_pull($key, $default = null)
    {
        $session = app(Session::class);

        return $session->pull($key, $default);
    }
}

if (!function_exists('session_delete')) {

    /**
     *  Delete value from session
     */
    function session_delete($key, $default = null)
    {
        $session = app(Session::class);

        return $session->delete($key, $default);
    }
}

if (!function_exists('errors')) {

    /**
     *  Return unserialized message bag object
     * @return \Illuminate\Foundation\Application|mixed
     */
    function errors(){
        if (session_has('_errors')){
            $errors = unserialize(sessiondata('_errors'));
            session_delete('_errors');
            return $errors;

        } else {
            return app(\Core\Libs\Support\MessageBag::class);
        }

    }
}

if (!function_exists('old')) {

    function old($key){
        if (session_has('_old_input.'.$key)) {
            $old = sessionData('_old_input.'.$key);
            session_delete('_old_input.'.$key);
            return $old;
        }
    }
}
