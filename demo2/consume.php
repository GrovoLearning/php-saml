<?php
/**
 * SAMPLE Code to demonstrate how to handle a SAML assertion response.
 *
 * The URL of this file will have been given during the SAML authorization.
 * After a successful authorization, the browser will be directed to this
 * link where it will send a certified response via $_POST.
 */

error_reporting(E_ALL);

require_once '../_toolkit_loader.php';

try {
    if (isset($_POST['SAMLResponse'])) {
        $samlSettings = new OneLogin_Saml2_Settings();
        $samlResponse = new OneLogin_Saml2_Response($samlSettings, $_POST['SAMLResponse']);

        if ($samlResponse->isValid()) {
            $attributes = $samlResponse->getAttributes();
            $attributes['nameId'] = $samlResponse->getNameId();

            header('Content-Type: application/json');
            echo json_encode($attributes);
        } else {
            echo 'Invalid SAML Response';
        }
    } else {
        echo 'No SAML Response found in POST.';
    }
} catch (Exception $e) {
    echo 'Invalid SAML Response: ' . $e->getMessage();
}
