<?php
/**
 * Here you will define your own functions
 */
function formsubmit()
{
    if (!formData == null) {
        if (defined("LIGHTWEB_DB") && LIGHTWEB_DB) {
            // Save into DB
        }
        response(true, formData);
    } else {
        response(false, [], 1, "formData Missing or JSON Format is incorrect");
    }
}