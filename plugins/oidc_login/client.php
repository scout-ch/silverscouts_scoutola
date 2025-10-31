<?php
  use OpenIDConnect\Client;
  use OpenIDConnect\Config as OpenIdConnectConfig;
  use OpenIDConnect\Issuer;
  use OpenIDConnect\Metadata\ClientMetadata;
  use OpenIDConnect\Metadata\ProviderMetadata; 


  require_once osc_plugin_folder(__FILE__) . 'vendor/autoload.php';

// function oidc_initialize() {


//   $oidc = new OpenIDConnectClient('https://id.provider.com',
//                                   'ClientIDHere',
//                                   'ClientSecretHere');
//   $oidc->providerConfigParam(['token_endpoint'=>'https://id.provider.com/connect/token']);
//   $oidc->addScope(['my_scope']);
//   $oidc->addAuthParam(['username'=>'<Username>']);
//   $oidc->addAuthParam(['password'=>'<Password>']);

//   //Perform the auth and return the token (to validate check if the access_token property is there and a valid JWT) :
//   // $token = $oidc->requestResourceOwnerToken(TRUE)->access_token;

//   return $oidc;
// }
