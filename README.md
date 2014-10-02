# disqus-demo

## Dependencies

* PHP 5.4+
* PHP Memcache extension (not Memcached)
* PHP Mongo extension
* Memcached
* MongoDB

If you do not have these dependencies and are on Mac OS X, you can quickly install them with [Homebrew](http://brew.sh/):

```
brew install php55
brew install php55-memcache
brew install php55-mongo
brew install memcached
brew install mongodb
```

Be sure to follow the post-install instructions provided by Homebrew to start the memcached and mongod servers.

## Install with Composer

```
$ composer create-project compwright/disqus-demo ./disqus-demo
$ cd disqus-demo
$ cp .env-sample .env
```

## Configuration

1. Create a [Disqus Application](https://disqus.com/api/applications/register/), and put the API key, API secret, and access token in your `.env` file
2. [Register your website on Disqus](https://disqus.com/admin/create/), and put the forum name (the Disqus subdomain you chose) in your `.env` file
3. Start the built-in PHP server by running the `start-server.sh` file

You're off to the races!
