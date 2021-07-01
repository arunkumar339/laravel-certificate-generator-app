<?php

function ferror($errors, $field)
{
    if ($errors->has($field)) {
        $error = str_replace(".", " ", $errors->first($field));
        return "<span class='help-block error'>$error</span>";
    }
}
