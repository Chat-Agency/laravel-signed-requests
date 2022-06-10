# Laravel Signed Requests

A Laravel package to help make signed requests between two laravel apps easier.

---

## Requirements

You need to have openssl installed. We use hash_hmac() function to create the signature from the payloads and validate the submitted signature. Review the potentially available algorithms you can use [here](https://www.php.net/manual/en/function.hash-hmac-algos.php).

The package includes:
A config file:

> Allows you to define request types by name. And give each type of request a unique signature secret string to use when validating signed request payloads.

A working middleware validator:

> This middleware works for the 'request' type as a default. You can copy this middleware after publishing, and use it as the baseline to create your own signed request validating middleware.

To start, after installing with composer, run:

```
artisan vendor:publish --provider=ChatAgency\LaravelSignedRequests\LaravelSignedRequestsServiceProvider --tag=laravel-signed-requests
```

This will give you the config file `config/signed-requests.php` as well as the middleware, that will be copied into your app's middleware folder.

As a test, you can use this curl call after installation, with all the default configuration.

The package registers a middleware that is aliased to: `signed-requests`. Create a controller and assign this middleware to a post route that uses this controller.

You can then post a simple test payload to this route:

```
curl --request POST \
  --url //{YOUR_LOCAL_ENV_SITE_URL_MAYBE?}/api/test \
  --header 'Content-Type: application/json' \
  --header 'X-BaseSignature: 2fde638dd705cc78c2cfe315f395b91ce3e09b2c9637f200cae65fc3041f4e4f' \
  --data '{"test":1}'
```

You will get an unauthorized http code if the signature is not passing the middleware's validation.
