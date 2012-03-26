# RedisMon

RedisMon can be used monitor redis vitals and push them into Librato (Yay!).

## What does it do?

RedisMon collects stats from one or multiple redis-servers. The statistics are pushed on a certain internal to Librato's metric service.

![Example Dashboard](http://f.cl.ly/items/1z0Q2V1N2p3n3Y0o3c1T/Screen%20Shot%202012-03-26%20at%208.54.13%20PM.png)

## How does it work?

 1. Clone this repo into `$HOME`
 2. `php composer.phar install`
 3. Copy `etc/redis-mon.ini-dist` to `etc/redis-mon.ini` and make changes (redis-servers, librato credentials)
 4. Review crontab in `var/cron` and install it (`crontab var/cron && crontab -l`)
 5. Profit!

Note: The period in `etc/redis-mon.ini` should match the interval in your crontab.

## Testing

This is an early Alpha release, to test RedisMon, run `./bin/redis-mon` (after you installed all dependencies with composer).

Requirements:

 * PHP 5.3.3
 * cURL (with ssl, etc.)

## TODO

 * Support statsd/graphite.
