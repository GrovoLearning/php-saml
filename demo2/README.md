# demo2

## Playbook
- Configure IdP

- Configure SP
    - IdP
        - `$settingsInfo['idp']['entityId']`
            - https://idp.ssocircle.com/
        - << Read File >>
        - `$settingsInfo['idp']['singleSignOnService']['url']`
            - `/EntityDescriptor/IDPSSODescriptor/SingleSignOnService[contains(@Binding, 'HTTP-Redirect')]/@Location`
        - `$settingsInfo['idp']['singleLogoutService']['url']`
            - `/EntityDescriptor/IDPSSODescriptor/SingleLogoutService[contains(@Binding, 'HTTP-Redirect')]/@Location`
        - `$settingsInfo['idp']['x509cert']`
            - `/EntityDescriptor/IDPSSODescriptor/KeyDescriptor[@use="signing"]/ds:KeyInfo/ds:X509Data/ds:X509Certificate/text()`
            - `/EntityDescriptor/IDPSSODescriptor/KeyDescriptor[@use="encryption"]/ds:KeyInfo/ds:X509Data/ds:X509Certificate/text()`
    - SP
        - `$spBaseUrl`
            - http://localhost:8000
- **$** `php -S localhost:8000`

### SSOCircle - Log in
POST https://idp.ssocircle.com/sso/UI/Login

#### Headers
Content-Type:application/x-www-form-urlencoded

#### Form Data
IDToken1:{{username}}
IDToken2:{{password}}

#### Response
302 (Browser)
200 (Postman)
Set-cookie:SSOCSession=AQIC5wM2LY4SfcwORt1kQQsPXh1UuOcY54snHXRWKHq4WMM.*AAJTSQACMDIAAlNLABM4Nzc4OTcwOTEzNTQxODIwNTI1AAJTMQACMDE.*;path=/;secure;httponly

### SSOCircle - Add SP
POST https://idp.ssocircle.com/sso/hos/SPMetaImportIP.jsp

#### Form Data
spfqdn:http://localhost:8000
spmetaAttr:FirstName
spmetaAttr:LastName
spmetaAttr:EmailAddress
spmetaxml:<?xml version="1.0" encoding="UTF-8"?>
<md:EntityDescriptor xmlns:md="urn:oasis:names:tc:SAML:2.0:metadata" validUntil="2016-10-13T18:51:53Z" cacheDuration="PT604800S" entityID="http://localhost:8000/demo2/metadata.php">
    <md:SPSSODescriptor AuthnRequestsSigned="false" WantAssertionsSigned="false" protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">
        <md:SingleLogoutService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect" Location="http://localhost:8000/demo2/slo.php" />
        <md:NameIDFormat>urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified</md:NameIDFormat>
        <md:AssertionConsumerService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST" Location="http://localhost:8000/demo2/consume.php" index="1" />
    </md:SPSSODescriptor>
</md:EntityDescriptor>
<!-- aka {$spBaseUrl}/demo2/metadata.php -->

#### Response
200

### SSOCircle - Clean Up
POST https://idp.ssocircle.com/sso/hos/ModifySPMetadata.jsp

#### Headers
Content-Type:application/x-www-form-urlencoded

#### Form Data
spmetadata:http://localhost:8000/demo2/metadata.php
action:Remove Metadata

#### Response
302 (Browser)
