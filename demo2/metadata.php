<?php
/**
 * SAMPLE Code to demonstrate how to handle a SAML assertion response.
 *
 * Your IdP will usually want your metadata, you can use this code to generate it once,
 * or expose it on a URL so your IdP can check it periodically.
 */

error_reporting(E_ALL);

require_once '../_toolkit_loader.php';

try {
    // $auth = new OneLogin_Saml2_Auth($settingsInfo);
    // $samlSettings = $auth->getSettings();

    // Now we only validate SP settings
    $samlSettings = new OneLogin_Saml2_Settings();

    // $sp = $samlSettings->getSPData();
    // $samlMetadata = OneLogin_Saml2_Metadata::builder($sp);
    $samlMetadata = $samlSettings->getSPMetadata();

    $errors = $samlSettings->validateMetadata($samlMetadata);

    if (empty($errors)) {
        header('Content-Type: text/xml');
        echo $samlMetadata;
    } else {
        throw new OneLogin_Saml2_Error(
            'Invalid SP metadata: '.implode(', ', $errors),
            OneLogin_Saml2_Error::METADATA_SP_INVALID
        );
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
